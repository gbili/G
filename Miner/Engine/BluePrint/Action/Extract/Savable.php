<?php
/**
 * This class is not meant for any great work, just to ensure
 * that the action gets all its data. And that it gets saved properly
 * 
 * @author gui
 *
 */
class G_Miner_Engine_BluePrint_Action_Extract_Savable
extends G_Miner_Engine_BluePrint_Action_Savable_Abstract
{
	/**
	 * 
	 * @var unknown_type
	 */
	const NO_INTERCEPT_METHOD = 0;
	
	/**
	 * 
	 * @param unknown_type $bPId
	 * @param unknown_type $parentId
	 * @param unknown_type $data
	 * @return unknown_type
	 */
	public function __construct()
	{
		parent::__construct();
		//set the type on construction forced by parent
		$this->_setElement('type', (integer) G_Miner_Engine_BluePrint::ACTION_TYPE_EXTRACT);
	}
	
	/**
	 * 
	 * @param unknown_type $bool
	 * @return unknown_type
	 */
	public function setUseMatchAll($bool)
	{
		$this->_setElement('useMatchAll', $bool);
	}

	/**
	 * 
	 * @return unknown_type
	 */
	public function getUseMatchAll()
	{
		return $this->getElement('useMatchAll');
	}
	
	/**
	 * 
	 * @param array $array
	 * @return unknown_type
	 */
	public function setGRM(G_Miner_Engine_BluePrint_Action_Extract_GRM $b)
	{
		$this->_setElement('gRM', $b);
	}
	
	/**
	 * Autoset
	 * 
	 * @return unknown_type
	 */
	public function getGRM()
	{
		if (!$this->hasGRM()) {
			$this->setGRM(new G_Miner_Engine_BluePrint_Action_Extract_GRM());
		}
		return $this->getElement('gRM');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasGRM()
	{
		return $this->isSetKey('gRM');
	}
	
	/**
	 * Proxy + added functionality (allow to set entity and method intercept at same time)
	 * 
	 * @param $group
	 * @param $entity
	 * @param $param3
	 * @param $param4
	 * @return $this
	 */
	public function spitGroupAsEntity($group, $entity, $param3 = false, $param4 = self::NO_INTERCEPT_METHOD)
	{
		//user wants to use param3 as isOptional and param4 as $resultInterceptMethod
		if (is_bool($param3)) {
			$isOptional = $param3;
			$resultInterceptMethod = (is_string($param4))? $param4 : self::NO_INTERCEPT_METHOD;
		//user used 3 paramter as resultInterceptMethod, and wants the 4th param to be isOptional default value
		} else if (is_string($param3)) {
			$isOptional = (is_bool($param4))? $param4 : false;
			$resultInterceptMethod = $param3;
		} else {
			throw new G_Miner_Engine_BluePrint_Action_Extract_Savable_Exception('3 and 4 Parameter values not supported');
		}

		//set mapping to entity
		$this->getGRM()->spitGroupAsEntity($group, $entity, $isOptional);
		
		//allow the result to be intercepted before spitting,
		//this can also be done by calling $this->interceptGroupsOneByOne
		if (self::NO_INTERCEPT_METHOD !== $resultInterceptMethod) {
			if (!is_string($resultInterceptMethod)) {
				throw new G_Miner_Engine_BluePrint_Action_Extract_Savable_Exception('$resultInterceptMethod (4th param) must be a string');
			}
			$this->getGRM()->interceptGroupsOneByOne($group, $resultInterceptMethod);
		}
		return $this;
	}
	
	/**
	 * Proxy
	 * 
	 * @param unknown_type $groups
	 * @param unknown_type $methodName
	 * @return $this
	 */
	public function interceptGroupsOneByOne($groups, $methodName)
	{
		$this->getGRM()->interceptGroupsOneByOne($groups, $methodName);
		return $this;
	}
	
	/**
	 * Proxy
	 * 
	 * @param array $groups
	 * @param unknown_type $methodName
	 * @return $this
	 */
	public function interceptGroupsTogether(array $groups, $methodName)
	{
		$this->getGRM()->interceptGroupsTogether($groups, $methodName);
		return $this;
	}
	
	
}