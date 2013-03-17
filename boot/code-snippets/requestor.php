<?php
/**
 * Insert multiple rows
 * //FIRST WAY using variables
		$i = 0;
		$j = count($mapping); //so i and j are never the same
		foreach ($mapping as $regexGroup => $const) {
			$sql .= "(:actionId, :" . $i .", :" . $j . "),";
			$values[':' . $i] = $regexGroup;
			$values[':' . $j] = $const;
			$i++;$j++;
		}
		$values[':actionId'] = $actionId;
		$sql = mb_substr($sql, 0, -1); //remove the last ","
 * 
 * 
 * @author gui
 *
 */

	/**
	 * Returns all the rows in tha Actions table
	 * where the authority is the same as specified
	 * in the url
	 * 
	 * @param G_Engine_Url $url
	 * @return actionId
	 */
	/*public*/ function saveAction(array $actionData)
	{
		// kinship checks
		$isRootAction = false;
		if (!isset($actionData['bluePrintId'])) {
			throw new G_Engine_BluePrint_Creator_Db_Exception('You must provide a key : bPId, with the bluePrint id to be able to save the action given : ' . print_r($actionData,true));
		}
		
		if (isset($actionData['parentActionId'])) {
			if (!$this->existsAction((integer) $actionData['parentActionId'])) {
				throw new G_Engine_BluePrint_Creator_Db_Exception('The parentActionId is does not exist given : ' . print_r($actionData['parentActionId'], true));
			}
		} else { //if has no parentActionId set, it means it is the root
			//ensure the bluePrint has not already a root action
			if ($this->existsAnyActionForBluePrint($actionData['bluePrintId'])) {
				throw new G_Engine_BluePrint_Creator_Db_Exception('No parentActionId given, means this is intended to be the root action, prblem is : bluePrint has already a root action, to solve this, pass a parentActionId');
			}
			//this action is intended to be the root action, check if ok
			//only G_Engine_BluePrint::ACTION_TYPE_GETCONTENTS can be root actions
			if (!isset($actionData['type'])) {
				throw new G_Engine_BluePrint_Creator_Db_Exception('You must provide the key \'type\', given : ' . print_r($actionData, true));
			}
			if ($actionData['type'] !== G_Engine_BluePrint::ACTION_TYPE_GETCONTENTS) {
				throw new G_Engine_BluePrint_Creator_Db_Exception('Only actions of type G_Engine_BluePrint::ACTION_TYPE_GETCONTENTS can be root');
			}
			//everitying ok
			$isRootAction = true;
			//create an entry for the parentActionId so first insert doesn't crash
			$actionData['parentActionId'] = 0;//0 is the default value, but it will be updated later on (once the action has been inserted and we know its id)
		}
		//1. General insert
		$sql = 'INSERT INTO BPAction
					(bPParentActionId, bPId, inputParentRegexGroupNumber, type, useMatchAll)
					VALUES (:parentActionId, :bleuPrintId, :inputParentRegexGroupNumber, :type, :useMatchAll)';
		$requiredKeys = array('parentActionId'=>true, 'bleuPrintId'=>true,'inputParentRegexGroupNumber'=>true, 'type'=>true, 'useMatchAll'=>true);
		//ensure all the needed data is present
		$missingEntries = array_diff_key($actionData, $requiredKeys);
		if (!empty($missingEntries)) {
			throw new G_Engine_BluePrint_Creator_Db_Exception('you have not provided all the data missing keys : ' . print_r($missingEntries, true));
		}
		//only pass the needed values, leave out the rest, like 'data' for later insert (once we have the actionId)
		$usefulEntries = array_intersect_key($requiredKeys, $actionData);
		foreach ($usefulEntries as $key => $value) {
			$varToValues[':' . $key] = $value;
		}
		$this->insertUpdateData($sql, $varToValues);
		//remember the inserted action id
		$actionId = $this->getAdapter()->lastInsertId();
		if (!is_numeric($actionId)) {
			throw new G_Engine_BluePrint_Creator_Db_Exception('lastInsertId() did not return a numeric');
		}
		//2. UPDATE parentAction id if root action
		if (true === $isRootAction) {
			$sql = 'UPDATE BPAction
						SET bPParentActionId = :actionId
						WHERE bPActionId = :actionId';
			$this->insertUpdateData($sql, array(':actionId' => $actionId));
		}
		//3. particular insert only if 'type' === G_Engine_BluePrint::ACTION_TYPE_EXTRACT
		if ($actionData['type'] === G_Engine_BluePrint::ACTION_TYPE_EXTRACT) {
			if (!isset($actionData['data'])) {
				throw new G_Engine_BluePrint_Creator_Db_Exception('When the action is of type G_Engine_BluePrint::ACTION_TYPE_EXTRACT, you must pass the key \'data\' along with the $actionData, given: ' . print_r($actionData, true));
			}
			$sql = 'INSERT INTO BPAction_r_Data
						(bPActionId, data)
						VALUES (:actionId, :data)';
			$this->insertUpdateData($sql, array(':actionId' => $actionId, ':data' => (string) $actionData['data']));
		}
		//4. Update the BluePrint NewInstanceStartingPointActionId if available
		if (isset($actionData['isNewInstanceGeneratingPoint'])) {
			$this->setBluePrintNewInstanceGeneratingPointActionId($actionData['bPId'], $actionId);
		}
		return $actionId;
	}