<?php
/**
 * 
 * @author gui
 *
 */
class G_Miner_Engine_BluePrint_Action_Savable_Db_Req
extends G_Db_Req_Abstract
{
	
	const DEFAULT_NO_INPUT_PARENT_REGEX_GROUP_NUMBER = 0;
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * For the actions of type extract, the result is returned
	 * as an indexed array with the group number as key and the
	 * result as value.
	 * For that type of array results, this function will return
	 * an array mapping the group number in result to the name
	 * of the entity.
	 * Ex : extract result : array(0=>'whole group', 1=>'Big lebowsky', 2=>'johny depp')
	 * 		mapping : array(1=>'Title', 2=>'Actor')
	 * then the two arrays should be combined to get an array like
	 * 		final : array('Title'=>'Big Lebowsky', 'Actor'=>'Johnny Depp')
	 * but this is done from bluePrint, not from here.
	 * This returns the mapping array.
	 * 
	 * @param integer $actionId the id of the action in Db
	 * @return array
	 */
	private function _setActionGroupToEntityMapping(array $mapping, $actionId)
	{
		$sql = "INSERT 
					INTO BPAction_RegexGroup_r_Const
						(bPActionId, regexGroup, const, isOpt)
					VALUES ";
		$varToValues = array();
		foreach ($mapping as $k => $arr) {
			$sql .= '(?, ?, ?, ?),';
			$varToValues[] = $actionId;
			$varToValues[] = $arr['regexGroup'];//group integer
			$varToValues[] = $arr['entity'];//const
			$varToValues[] = (integer) $arr['isOpt'];//optional is bool change it to 0 or 1
		}
		$sql = mb_substr($sql, 0, -1); //remove the trailing ","
		$this->insertUpdateData($sql, $varToValues);
	}
	
	/**
	 * 
	 * @param array $mapping
	 * @param unknown_type $actionId
	 * @param unknown_type $bPId
	 * @return unknown_type
	 */
	private function _setActionGroupToMethodMethodMapping(array $mapping, $actionId, $bPId)
	{
		$sql = "INSERT
				INTO BPAction_RegexGroup_r_MethodMethod
					(bPActionId, regexGroup, methodId, interceptType) VALUES ";
		$varToValues = array();
		foreach ($mapping as $k => $arr) {
			$sql .= '(?, ?, ?, ?),';
			$varToValues[] = $actionId;
			$varToValues[] = (integer) $arr['regexGroup'];//group integer
			$varToValues[] = $this->_saveMethodMethodAndGetId($arr['methodName'], $bPId);
			$varToValues[] = (integer) $arr['interceptType'];//optional is bool change it to 0 or 1
		}
		$sql = mb_substr($sql, 0, -1); //remove the trailing ","
		$this->insertUpdateData($sql, $varToValues);
	}
	
	/**
	 * 
	 * @param unknown_type $methodName
	 * @param unknown_type $bPId
	 * @return unknown_type
	 */
	private function _saveMethodMethodAndGetId($methodName, $bPId)
	{
		if (false === $id = $this->_existsMethodMethod($methodName, $bPId, true)) {
			$sql = "INSERT INTO BluePrint_MethodMethod (bPId, name) VALUES (:bPId, :methodName)";
			$this->insertUpdateData($sql, array('bPId' => $bPId,':methodName' => $methodName));
			$id = $this->getAdapter()->lastInsertId();
		}
		return $id;
	}
	
	/**
	 * 
	 * @param unknown_type $methodName
	 * @param unknown_type $bPId
	 * @param unknown_type $returnIdOrFalse
	 * @return unknown_type
	 */
	private function _existsMethodMethod($methodName, $bPId, $returnIdOrFalse)
	{
		$sql = "SELECT m.methodId AS methodId
					FROM BluePrint_MethodMethod AS m
					WHERE m.bPId = :bPId AND m.name = :methodName";
		return $this->existsElement($sql,
									array(':bPId' => (integer) $bPId,
										  ':methodName' => $methodName),
									'methodId',
									(boolean) $returnIdOrFalse);
	}
	
	/**
	 * 
	 * @param array $mapping
	 * @param unknown_type $actionId
	 * @return unknown_type
	 */
	private function _saveCallbackMapping(array $mapping, $actionId)
	{
		$sql = "INSERT INTO BPAction_RegexGroup_r_CallbackMethod_ParamNum (bPActionId, paramNum, regexGroup) VALUES ";
		$varToValues = array();
		foreach ($mapping as $paramNum => $group) {
			$sql .= '(?, ?, ?),';
			$varToValues[] = $actionId;
			$varToValues[] = $paramNum;
			$varToValues[] = $group;
		}
		$sql = mb_substr($sql, 0, -1);//remove trailing ','
		$this->insertUpdateData($sql, $varToValues);
	}
	
	/**
	 * 
	 * @param unknown_type $methodName
	 * @param unknown_type $actionId
	 * @return unknown_type
	 */
	private function _saveCallbackMethod($methodName, $actionId)
	{
		if (!$this->_existsCallbackMethod($methodName, $actionId)) {
			$sql = "INSERT INTO BPAction_CallbackMethod (bPActionId, methodName) VALUES (:actionId, :name)";
			$this->insertUpdateData($sql, array(':actionId' => $actionId, ':name' => $methodName));
		}
	}
	
	/**
	 * 
	 * @param unknown_type $methodName
	 * @param unknown_type $actionId
	 * @return unknown_type
	 */
	private function _existsCallbackMethod($methodName, $actionId)
	{
		$sql = "SELECT c.methodName AS methodName
					FROM BPAction_CallbackMethod AS c
					WHERE c.bPActionId = :actionId AND c.methodName = :methodName";
		return (false !== $this->getResultSet($sql, array(':actionId' => (integer) $actionId, ':methodName' => $methodName)));
	}
	
	/**
	 * 
	 * @param unknown_type $actionId
	 * @return unknown_type
	 */
	private function _existsAction($actionId, $returnIdOrFalse = false)
	{
		if (!is_numeric($actionId)) {
			throw new G_Db_Req_Exception("the action id must be numeric, given : " . print_r($actionId, true));
		}
		$sql = "SELECT a.bPActionId AS actionId
					FROM BPAction AS a
					WHERE a.bPActionId = :actionId";
		return $this->existsElement($sql,
									array(':actionId' => (integer) $actionId),
									'actionId',
									(boolean) $returnIdOrFalse);
	}
	
	/**
	 * 
	 * @param G_Miner_Engine_BluePrint_Action_Savable_Abstract $action
	 * @return unknown_type
	 */
	private function _insertActionAndSetId(G_Miner_Engine_BluePrint_Action_Savable_Abstract $action)
	{
		/*
		 * 1. General insert
		 *  There is no _existsAction check because it is not possible to
		 *  differenciate between two actions by other means than with the id
		 *  and the id is not available
		 */
		$sql = 'INSERT INTO BPAction
					(bPId, execRank, inputParentRegexGroupNumber, type, useMatchAll, isOpt, title)
					VALUES (:bluePrintId, :execRank, :inputParentRegexGroupNumber, :type, :useMatchAll, :isOpt, :title)';
		$this->insertUpdateData($sql, array(':bluePrintId' => $action->getBluePrint()->getId(),
											':execRank' => $action->getRank(),
											':inputParentRegexGroupNumber' => $action->getInputParentRegexGroupNumber(),
											':type' => $action->getType(),
											':useMatchAll' => (integer) (($action instanceof G_Miner_Engine_BluePrint_Action_Extract_Savable)? $action->getUseMatchAll() : 0),
											':isOpt' => (integer) $action->getIsOptional(),
											':title' => (($action->hasTitle())? $action->getTitle() : '')));
		$id = $this->getAdapter()->lastInsertId();
		//save the id in the object to make it available to childs
		$action->setId($id);
	}
	
	/**
	 * 
	 * @param G_Miner_Engine_BluePrint_Action_Blueprint_Abstract $action
	 * @return unknown_type
	 */
	public function save(G_Miner_Engine_BluePrint_Action_Savable_Abstract $action)
	{
		//is is intended to be the root action
		if (!$action->hasParent()) {
			//make sure it can be root action
			if ($action->getType() !== G_Miner_Engine_BluePrint::ACTION_TYPE_GETCONTENTS) {
				throw new G_Db_Req_Exception('Only actions of type G_Miner_Engine_BluePrint::ACTION_TYPE_GETCONTENTS can be root');
			}
			
			//ensure the bluePrint has not already a root action
			if ($this->_existsAnyActionForBluePrint($action->getBluePrint()->getId())) {
				print_r($action);
				throw new G_Db_Req_Exception('No parentActionId given, means this is intended to be the root action, prblem is : bluePrint has already a root action, to solve this, pass a parentActionId. You can also delete the bluePrint action set');
			}
			//now id is available
			$this->_insertActionAndSetId($action);
			//the root actions parent is itself
			$parentId = $action->getId();
		} else {
			$this->_insertActionAndSetId($action);
			//G_Molecule_Savable has saved all parent instances so now we can access the parent id without fear to crash
			$parentId = $action->getParent()->getId();
		}
		
		if ($action->injectsAction()) {
			$this->_saveInjection($action);
		}
		
		/*
		 * 2. Save kinship
		 */
		$this->_saveActionKinship($action->getId(), $parentId);
		/*
		 * 3. particular insert only if 'type' === G_Miner_Engine_BluePrint::ACTION_TYPE_EXTRACT and root
		 */
		if ($action->getType() === G_Miner_Engine_BluePrint::ACTION_TYPE_EXTRACT
		 || $action->isRoot()) {
			if (!$action->hasData()) {
				throw new G_Db_Req_Exception('When the action is of type G_Miner_Engine_BluePrint::ACTION_TYPE_EXTRACT or it is the root, you must call setData(), given: ' . print_r($actionData, true));
			}
			$sql = 'INSERT INTO BPAction_Data
						(bPActionId, data)
						VALUES (:actionId, :data)';
			$this->insertUpdateData($sql, array(':actionId' => $action->getId(), 
												':data' 	=> (string) $action->getData()));
		}
		
		/*
		 * 3. save group result mapping (group to entity && group to method)
		 */
		if ($action->getType() === G_Miner_Engine_BluePrint::ACTION_TYPE_EXTRACT
		 && $action->hasGRM()) {
			$this->_saveGRM($action);
		}
		
		/*
		 * 4.1 particular insert only for type GetContents for setting callback
		 */
		if ($action->getType() === G_Miner_Engine_BluePrint::ACTION_TYPE_GETCONTENTS
		 && $action->hasCallbackMethod()) {
		 	$this->_saveCallback($action);
		}

		/*
		 * 5. Update the BluePrint NewInstanceStartingPointActionId if available
		 */
		if ($action->isNewInstanceGeneratingPoint()) {
			G_Db_Registry::getInstance($action->getBluePrint())->updateBluePrintNewInstanceGeneratingPointActionId($action->getBluePrint()->getId(), $action->getId());
		}
	}
	
	/**
	 * 
	 * @param unknown_type $action
	 * @return unknown_type
	 */
	private function _saveInjection(G_Miner_Engine_BluePrint_Action_Savable_Abstract$action)
	{
		$this->insertUpdateData("INSERT INTO BPAction_r_InjectedBPAction (bPActionId, injectedActionId, inputGroup) VALUES (:id,:iId,:group)", 
								array(':id' => $action->getId(),
									  ':iId' => $action->getInjectedAction()->getId(),
									  ':group' => (($action->getInjectedAction()->hasInjectInputGroup())? 
														$action->getInjectedAction()->getInjectInputGroup() : 0)));
	}

	/**
	 * 
	 * @param $a
	 * @return unknown_type
	 */
	private function _saveGRM(G_Miner_Engine_BluePrint_Action_Extract_Savable $a)
	{
		if ($a->getGRM()->hasGroupToEntityMap()) {
			$this->_setActionGroupToEntityMapping($a->getGRM()->getGroupToEntityMap(), $a->getId());
		}
		if ($a->getGRM()->hasGroupToMethodMap()) {
			$this->_setActionGroupToMethodMethodMapping($a->getGRM()->getGroupToMethodMap(), $a->getId(), $a->getBluePrint()->getId());
		}
	}
	
	/**
	 * 
	 * @param $a
	 * @return unknown_type
	 */
	private function _saveCallback(G_Miner_Engine_BluePrint_Action_GetContents_Savable $a)
	{
		$this->_saveCallbackMethod($a->getCallbackMethod(), $a->getId());
		if ($a->hasCallbackMap()) {
			$this->_saveCallbackMapping($a->getCallbackMap(), $a->getId());
		}
	}
	
	/**
	 * 
	 * @param unknown_type $actionId
	 * @param unknown_type $data
	 * @return unknown_type
	 */
	public function updateActionInputData($actionId, $data)
	{
		$this->_checkInput($actionId, $data);
		$this->insertUpdateData("UPDATE BPAction_Data SET data = :data WHERE bPActionId = :id", array(':data' => $data, ':id' => $actionId));
	}
	
	/**
	 * 
	 * @param unknown_type $actionId
	 * @param unknown_type $data
	 * @return unknown_type
	 */
	private function _checkInput($actionId, $data = null)
	{
		if (null !== $data && !is_string($data)) {
			throw new G_Db_Req_Exception("data must be string, given : " . print_r($data, true));
		}
		if (false === $this->_existsAction($actionId)) {
			throw new G_Db_Req_Exception("action with id : $actionId, does not exit");
		}
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function saveNIGPLastInputData($actionId, $data, $errorTriggerActionId)
	{
		$this->_checkInput($actionId, $data);
		$a = array(':data' => $data, ':id' => $actionId, ':eId' => $errorTriggerActionId);
		if (false === $this->getNIGPLastInputData($actionId)) {
			$this->insertUpdateData("INSERT INTO BPAction_ErrorData (bPNIGPActionId, nIGPLastInputData, errorTriggerActionId) VALUES (:id,:data,:eId)", $a);
		} else {
			$this->insertUpdateData("UPDATE BPAction_ErrorData SET nIGPLastInputData = :data, errorTriggerActionId = :eId WHERE bPNIGPActionId = :id", $a);
		}
	}
	
	/**
	 * 
	 * @param unknown_type $actionId
	 * @return unknown_type
	 */
	public function getNIGPLastInputData($actionId)
	{
		return $this->getResultSet("SELECT b.bPNIGPActionId AS nIGPActionId,
										   b.nIGPLastInputData AS data,
										   b.errorTriggerActionId AS errorActionId 
									FROM BPAction_ErrorData AS b
									WHERE b.bPNIGPActionId = :id", array(':id' => $actionId));
	}
	
	/**
	 * 
	 * @param unknown_type $id
	 * @param unknown_type $parentId
	 * @return unknown_type
	 */
	private function _saveActionKinship($id, $parentId)
	{
		if (!($this->_existsAction($id) && $this->_existsAction($parentId))) {
			throw new G_Db_Req_Exception("At least one of the actions with id's: $id, $parentId do not exist.");
		}
		$sql = 'UPDATE BPAction SET bPParentActionId = :parentActionId WHERE bPActionId = :actionId';
		$this->insertUpdateData($sql, array(':actionId' => (integer) $id, ':parentActionId' => (integer) $parentId));
	}
	
	/**
	 * Tells whether there is any action for the given blue print id
	 * 
	 * @return bolean
	 */
	private function _existsAnyActionForBluePrint($bluePrintId)
	{
		$sql = "SELECT a.bPActionId AS actionId
					FROM BPAction AS a
					WHERE a.bPId = :bPId";
		return (boolean) $this->getResultSet($sql, array(':bPId' => (integer) $bluePrintId));
	}
}