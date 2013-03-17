<?php
class G_Miner_Engine_BluePrint_Db_Interface
{
	/**
	 * 
	 * @param G_Url_Authority_Host $host
	 * @return array : 'newInstanceGeneratingPointActionId',
	 * 				   'path',
	 * 				   'pathType',
	 * 				   'classType'
	 */
	public function getBluePrintInfo(G_Url_Authority_Host $host);
	
	/**
	 * 
	 * @param string | integer $injectedActionId
	 * @return array : 'actionId',
	 * 				   'inputGroup'
	 */
	public function getInjectionData($injectedActionId);
	
	/**
	 * 
	 * @param unknown_type $actionId
	 * @return array : 'regexGroup',
	 * 				   'entity',
	 * 				   'isOpt'
	 */
	public function getActionGroupToEntityMapping($actionId);
	
	/**
	 * 
	 * @param G_Url_Authority_Host $host
	 * @return array : 'actionId',
	 * 				   'parentId',
	 * 				   'inputGroupNum',
	 * 				   'type',
	 * 				   'useMatchAll'
	 * 				   'isOpt',
	 * 				   'title',
	 * 				   'data'
	 */
	public function getActionSet(G_Url_Authority_Host $host);
	
	/**
	 * 
	 * @param unknown_type $actionId
	 * @return array : 'methodName'
	 */
	public function getActionCallbackMethodName($actionId);
	
	/**
	 * 
	 * @param unknown_type $actionId
	 * @return array : 'regexGroup',
	 * 				   'paramNum'
	 */
	public function getActionCallbackParamsToGroupMapping($actionId);
	
	/**
	 * 
	 * @param unknown_type $actionId
	 * @return array : 'methodName',
	 * 				   'regexGroup',
	 * 				   'interceptType'
	 */
	public function getActionGroupToMethodNameAndInterceptType($actionId);
}