<?php
/**
 * 
 * @author gui
 *
 */
class G_Miner_Engine_BluePrint_Savable_Db_Req
extends G_Db_Req_Abstract
{
	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 
	 * @param G_Miner_Engine_BluePrint_Savable $bluePrint
	 * @return unknown_type
	 */
	public function save(G_Miner_Engine_BluePrint_Savable $b)
	{
		//don't save if it already exists
		$res = $this->existsBluePrint($b->getHost()->toString(), true);
		//if it doesn't exist make insert
		if (false === $res) {
			if (!$b->hasNewInstanceGeneratingPointAction()) {
				throw new G_Db_Req_Exception('The bluePrint must hasve a new instance generating point action');
			}
			$sql = 'INSERT INTO BluePrint
						(host)
						VALUES (:host)';
			$this->insertUpdateData($sql, 
									array(':host' => $b->getHost()->toString()));
			$res = $this->getAdapter()->lastInsertId();
			//save the paths used in callbacks and methods
			$this->_savePaths($b, $res);
		}
		//set the bluePrint id
		$b->setId($res);
	}
	
	/**
	 * These are the paths where the Method and Callback classes files are
	 * 
	 * @param $b
	 * @param $bPId
	 * @return unknown_type
	 */
	private function _savePaths(G_Miner_Engine_BluePrint_Savable $b, $bPId)
	{
		$bPId = (integer) $bPId;
		$sql = 'INSERT INTO BluePrint_CMPaths (bPId, path, pathType, classType) VALUES (:bPId, :path, :pathType, :classType)';
		$paths = array();
		if ($b->hasMethodPath()) {
			$paths[] = array(':path' => $b->getMethodPath(),
							 ':pathType' => G_Miner_Engine_BluePrint_Action_CMLoader::PATH_TYPE_DIRECT,
							 ':classType' => G_Miner_Engine_BluePrint_Action_CMLoader::CLASS_TYPE_METHOD,
							 ':bPId' => $bPId);
		}
		if ($b->hasCallbackPath()) {
			$paths[] = array(':path' => $b->getCallbackPath(),
							 ':pathType' => G_Miner_Engine_BluePrint_Action_CMLoader::PATH_TYPE_DIRECT,
							 ':classType' => G_Miner_Engine_BluePrint_Action_CMLoader::CLASS_TYPE_CALLBACK,
							 ':bPId' => $bPId);
		}
		if ($b->hasBasePath()) {
			$paths[] = array(':path' => $b->getBasePath(),
							 ':pathType' => G_Miner_Engine_BluePrint_Action_CMLoader::PATH_TYPE_BASE,
							 ':classType' => 0,
							 ':bPId' => $bPId);
		}
		foreach ($paths as $path) {
			$this->insertUpdateData($sql,
									$path);
		}
	}
	
	/**
	 * 
	 * @return bool | integer
	 */
	public function existsBluePrint($hostOrId, $returnId = false)
	{
		$column = (is_numeric($hostOrId))? 'bPId' : 'host';
		$sql = "SELECT b.bPId as bPId
					FROM BluePrint AS b
					WHERE b.$column = :column";
		return $this->existsElement($sql,
									array(':column' => $hostOrId),
									'bPId',
									(boolean) $returnId);
	}
	
	/**
	 * 
	 * @param $bluePrintId
	 * @param $actionId
	 * @return unknown_type
	 */
	public function updateBluePrintNewInstanceGeneratingPointActionId($bluePrintId, $actionId)
	{
		if (!G_Db_Registry::getInstance('G_Miner_Engine_BluePrint_Savable')->existsBluePrint((integer) $bluePrintId)) {
			throw new G_Db_Req_Exception('You are trying to update an unexisting bluePrint\'s newInstanceGeneratingPointActionId given bPId : ' . print_r($bluePrintId, true));
		}
		$sql = "UPDATE BluePrint
					SET newInstanceGeneratingPointActionId = :actionId
					WHERE bPId = :bPId";
		return $this->insertUpdateData($sql, array(':actionId' => (integer) $actionId,
												   ':bPId'	   => (integer) $bluePrintId));
	}
}