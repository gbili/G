<?php
/**
 * 
 * @author gui
 *
 */
class G_Miner_Engine_BluePrint_Action_GetContents_Savable
extends G_Miner_Engine_BluePrint_Action_Savable_Abstract
{
	/**
	 * 
	 * @return unknown_type
	 */	
	public function __construct()
	{
		parent::__construct();
		$this->_setElement('type', G_Miner_Engine_BluePrint::ACTION_TYPE_GETCONTENTS);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasCallbackMethod()
	{
		return $this->isSetKey('callbackMethod');
	}
	
	/**
	 * 
	 * @param unknown_type $methodName
	 * @return unknown_type
	 */
	public function setCallbackMethod($methodName)
	{
		if (!is_string($methodName)) {
			throw new G_Miner_Engine_BluePrint_Action_Savable_Exception('the method name must be passed as a string');
		}
		if (!$this->hasBluePrint()) {
			throw new G_Miner_Engine_BluePrint_Action_Savable_Exception('The bluePrint must be set in order tu map the action to its callback method');
		}
		if ($this->getBluePrint()->hasCallbackPath()) {
			$path = $this->getBluePrint()->getCallbackPath();
			$type = G_Miner_Engine_BluePrint_Action_CMLoader::PATH_TYPE_DIRECT;
		} else if ($this->getBluePrint()->hasBasePath()) {
			$path = $this->getBluePrint()->getBasePath();
			$type = G_Miner_Engine_BluePrint_Action_CMLoader::PATH_TYPE_BASE;
		} else {
			throw new G_Miner_Engine_BluePrint_Action_Savable_Exception('There is no way to find the callback class if no path is provided in bluePrint');
		}
		if (!is_string(($className = G_Miner_Engine_BluePrint_Action_CMLoader::loadCallbackClass($path, $this->getBluePrint()->getHost(), $type)))) {
			throw new G_Miner_Engine_BluePrint_Action_Savable_Exception('the class could not be loaded errors : ' . print_r(G_Miner_Engine_BluePrint_Action_CMLoader::getErrors(), true));
		}
		if (false === G_Miner_Engine_BluePrint_Action_CMLoader::methodExists($className, $methodName)) {
			throw new G_Miner_Engine_BluePrint_Action_Savable_Exception("the method '$methodName' does not exist in $className");
		}
		$this->_setElement('callbackMethod', $methodName);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getCallbackMethod()
	{
		return $this->getElement('callbackMethod');
	}
	
	/**
	 * 
	 * @param array $mapping
	 * @return unknown_type
	 */
	public function setCallbackMap(array $mapping)
	{
		if (array_keys($mapping) !== range(0, count($mapping) - 1)) {
			throw new G_Miner_Engine_BluePrint_Action_GetContents_Callback_Exception('Mapping not supported, keys should range from 0 to n');
		}
		$this->_setElement('callbackMapping', $mapping);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getCallbackMap()
	{
		return $this->getElement('callbackMap');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasCallbackMap()
	{
		if ($ret = $this->isSetKey('callbackMap')) {
			$ret = $this->getElement('callbackMap');
			$ret = !empty($ret);//ensure not empty array
		}
		return $ret;
	}
}