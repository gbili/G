<?php
class G_Miner_Engine_BluePrint_Db_Req
extends G_Db_Req_Abstract
implements G_Miner_Engine_BluePrint_Db_Interface
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
	 * This will get the paths an new instance
	 * generating point action id
	 * 
	 * @param G_Url_Authority_Host $host
	 * @return unknown_type
	 */
	public function getBluePrintInfo(G_Url_Authority_Host $host)
	{
		return $this->getResultSet("SELECT b.bPId AS bPId,
									   b.newInstanceGeneratingPointActionId AS newInstanceGeneratingPointActionId,
									   cm.path AS path,
									   cm.pathType AS pathType,
									   cm.classType AS classType
									FROM BluePrint AS b 
										LEFT JOIN BluePrint_CMPaths AS cm 
											ON (b.bPId = cm.bPId)
									WHERE b.host = :host",
								  array(':host' => $host->toString()));
	}
	
	/**
	 * 
	 * @param unknown_type $injectedActionId
	 * @return unknown_type
	 */
	public function getInjectionData($injectedActionId)
	{
		return $this->getResultSet("SELECT b.bPActionId AS actionId,
										   b.inputGroup AS inputGroup 
									FROM BPAction_r_InjectedBPAction AS b 
									WHERE b.injectedActionId = :id",
									array(':id' => (integer) $injectedActionId));
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
	public function getActionGroupToEntityMapping($actionId)
	{
		$sql = "SELECT b.regexGroup AS regexGroup, 
					   b.const AS entity,
					   b.isOpt AS isOpt
					FROM BPAction_RegexGroup_r_Const AS b
					WHERE b.bPActionId = :actionId";
		return $this->getResultSet($sql, array(':actionId' => $actionId));
	}
	
	/**
	 * Returns all the rows in tha Actions table
	 * where the host is the same as specified
	 * in the url
	 * 
	 * @param G_Url_Authority_Host $host
	 * @return unknown_type
	 */
	public function getActionSet(G_Url_Authority_Host $host)
	{
		$sql = "SELECT a.bPactionId AS actionId,
					   a.bPParentActionId AS parentId,
					   a.inputParentRegexGroupNumber AS inputGroupNum,
					   a.type AS type,
					   a.useMatchAll AS useMatchAll,
					   a.isOpt AS isOpt,
					   a.title AS title,
					   d.data AS data
					FROM BluePrint AS b 
						INNER JOIN BPAction AS a ON (b.bPId = a.bPId)
						LEFT JOIN BPAction_Data AS d ON (a.bPActionId = d.bPActionId)
					WHERE b.host = :host
					ORDER BY a.execRank ASC";
		return $this->getResultSet($sql, array(':host' => $host->toString()));
	}
	
	/**
	 * 
	 * @param unknown_type $actionId
	 * @return unknown_type
	 */
	public function getActionCallbackMethodName($actionId)
	{
		return $this->getResultSet("SELECT c.methodName AS methodName
										FROM BPAction_CallbackMethod AS c 
										WHERE c.bPActionId = :bPActionId",
									array(':bPActionId' => $actionId));
	}
	
	/**
	 * 
	 * @param unknown_type $actionId
	 * @return unknown_type
	 */
	public function getActionCallbackParamsToGroupMapping($actionId)
	{	
		$sql = "SELECT d.regexGroup AS regexGroup,
					   d.paramNum AS paramNum
					FROM BPAction_RegexGroup_r_CallbackMethod_ParamNum AS d 
					WHERE d.bPActionId = :bPActionId 
					ORDER BY d.paramNum ASC";
		return $this->getResultSet($sql, array(':bPActionId' => $actionId));
	}
	
	/**
	 * 
	 * @param unknown_type $actionId
	 * @return unknown_type
	 */
	public function getActionGroupToMethodNameAndInterceptType($actionId)
	{
		$sql = "SELECT m.name AS methodName,
					   b.regexGroup AS regexGroup,
					   b.interceptType AS interceptType
					FROM BluePrint_MethodMethod as m
						LEFT JOIN BPAction_RegexGroup_r_MethodMethod as b
							ON (m.methodId = b.methodId)
					WHERE b.bPActionId = :actionId
					ORDER BY b.interceptType ASC, m.name ASC, b.regexGroup ASC";
		return $this->getResultSet($sql, array(':actionId' => $actionId));
		
	}
}