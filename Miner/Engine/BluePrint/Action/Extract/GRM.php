<?php
/**
 * GRM : Group Result Mapping
 * 
 * On extract actions, the result groups can be intercepted
 * and spitted as final results.
 * 
 * There are two ways of intercepting the groups witha method : 
 *  1. Together : 
 *  	All concerned groups are passed to the method as an array
 *  	and the method returns one single result that will replace
 *  	the group with the lowest number. All other concerned groups
 *  	will be removed from final result.
 *  	(some groups desapear).
 *  2. OnByOne :
 *  	The concerned groups are passed one by one to the method
 *  	and the result will replace the actual group in final results
 *  	(all groups are kept).
 *  
 *  The OneByOne intercept process takes place before the
 *  Together intercept process, to avoid trying to get
 *  missing groups.
 *  
 *  Once the intercept process has taken place the ones that are
 *  mapped to an entity will be spitted. As the intercept process
 *  removes some groups, this class will make sure that the groups
 *  that get removed are not intended to be spitted.
 * 
 * 
 * @author gui
 *
 */
class G_Miner_Engine_BluePrint_Action_Extract_GRM
{	
	/**
	 * Contains the group numbers that have been
	 * mapped to some entity
	 * 
	 * @var unknown_type
	 */
	private $_groupsMappedToSomeEntity = array();
	
	/**
	 * Contains all groups mapping to their entity
	 * and optional parameter
	 * 
	 * @var unknown_type
	 */
	private $_groupToEntityMap = array();
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_groupToInterceptMethodAndTypeMap = array();
	
	/**
	 * Groups already set
	 * 
	 * @var unknown_type
	 */
	private $_groupsAlreadyIntercepted = array();
	
	/**
	 * The groups that get intecepted together
	 * will all desapear except the one with
	 * the lowest number.
	 * 
	 * @var unknown_type
	 */
	private $_desapearingGroups = array();
	
	/**
	 * Don't make integrity checks twice
	 * if the input hasn't changed
	 * 
	 * @var unknown_type
	 */
	private $_integrityChecked = false;
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		$this->_groupsAlreadyIntercepted[G_Miner_Engine_BluePrint_Action_Extract_Method_Wrapper::INTERCEPT_TYPE_ONEBYONE] = array();
		$this->_groupsAlreadyIntercepted[G_Miner_Engine_BluePrint_Action_Extract_Method_Wrapper::INTERCEPT_TYPE_TOGETHER] = array();
	}
	
	/**
	 * 
	 * @param $group
	 * @param $entity
	 * @param $isOptional
	 * @return $this
	 */
	public function spitGroupAsEntity($group, $entity, $isOptional)
	{
		if (!is_int($group)) {
			throw new G_Miner_Engine_BluePrint_Action_Extract_GroupMapping_Exception('$group must be an integer');
		}
		if (!is_int($entity)) {
			throw new G_Miner_Engine_BluePrint_Action_Extract_GroupMapping_Exception('$entity must be an integer');
		}
		if (!is_bool($isOptional)) {
			throw new G_Miner_Engine_BluePrint_Action_Extract_GroupMapping_Exception('$isOptional must be a boolean');
		}
		//make sure groups are not repeated
		if (in_array($group, $this->_groupsAlreadyIntercepted)) {
			throw new G_Miner_Engine_BluePrint_Action_Extract_GroupMapping_Exception('The group with number ' . $group . ' is already set in the basket');
		} else {
			$this->_groupsMappedToSomeEntity[] = $group;
		}
		$this->_groupToEntityMap[] = array('regexGroup' => $group, 'entity' => $entity, 'isOpt' => $isOptional);
		$this->_integrityChecked = false;
		return $this;
	}
	
	/**
	 * 
	 * @param numeric | array $groups
	 * @param unknown_type $methodName
	 * @return unknown_type
	 */
	public function interceptGroupsOneByOne($groups, $methodName)
	{
		if (!is_array($groups)) {
			$groups = array($groups);
		}
		$this->_addToInterceptMap($groups, $methodName, G_Miner_Engine_BluePrint_Action_Extract_Method_Wrapper::INTERCEPT_TYPE_ONEBYONE);
	}
	
	/**
	 * 
	 * @param array $groups
	 * @param unknown_type $methodName
	 * @return unknown_type
	 */
	public function interceptGroupsTogether(array $groups, $methodName)
	{
		$this->_addToInterceptMap($groups, $methodName, G_Miner_Engine_BluePrint_Action_Extract_Method_Wrapper::INTERCEPT_TYPE_TOGETHER);
	}
	
	/**
	 * 
	 * @param array $groups
	 * @param unknown_type $methodName
	 * @param unknown_type $interceptType
	 * @return unknown_type
	 */
	private function _addToInterceptMap(array $groups, $methodName, $interceptType)
	{
		if (!is_string($methodName)) {
			throw new G_Miner_Engine_BluePrint_Action_Extract_GRM_Exception('the methodName must be a string given : ' . print_r($methodName, true));
		}

		$lowestGroup = false;
		foreach ($groups as $group) {
			if (!is_numeric($group)) {
				throw new G_Miner_Engine_BluePrint_Action_Extract_GRM_Exception('the groups must be passed as a numeric value in an array given : ' . print_r($groups, true));
			}
			$group = (integer) $group;
			//only allow one intercept per group per intercept type
			if (in_array($group, $this->_groupsAlreadyIntercepted[$interceptType])) {
				throw new G_Miner_Engine_BluePrint_Action_Extract_GRM_Exception('you can intercept a group only once per intercept type');
			}
			//add the group to intercepted
			$this->_groupsAlreadyIntercepted[$interceptType][] = $group;
			$this->_groupToInterceptMethodAndTypeMap[] = array('regexGroup' => (integer) $group, 'methodName' => $methodName, 'interceptType' => $interceptType);
			if (G_Miner_Engine_BluePrint_Action_Extract_Method_Wrapper::INTERCEPT_TYPE_TOGETHER === $interceptType) {
				if ($lowestGroup === false) {//first time
					$lowestGroup = $group;
				} else if ($lowestGroup > $group) {//set as lowest group
					$this->_desapearingGroups[] = $lowestGroup;//add the old lowest group to desapearing
					$lowestGroup = $group;
				} else {//group is higher it will desapear
					$this->_desapearingGroups[] = $group;
				}
			}
		}
		$this->_integrityChecked = false;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function checkIntegrity()
	{
		//only check if the together intercept type is being used
		if (!empty($this->_groupsAlreadyIntercepted[G_Miner_Engine_BluePrint_Action_Extract_Method_Wrapper::INTERCEPT_TYPE_TOGETHER])) {
			$a = array_intersect($this->_groupsMappedToSomeEntity, $this->_desapearingGroups);
			if (!empty($a)) {
				throw new G_Miner_Engine_BluePrint_Action_Extract_GRM_Exception('some groups whose destiny is to desapear are mapped to some entity, groups : ' . print_r($a, true));
			}
		}
		$this->_integrityChecked = true;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getGroupToEntityMap()
	{
		if (false === $this->_integrityChecked) {
			$this->checkIntegrity();
		}
		return $this->_groupToEntityMap;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasGroupToEntityMap()
	{
		return !empty($this->_groupToEntityMap);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getGroupToMethodMap()
	{
		if (false === $this->_integrityChecked) {
			$this->checkIntegrity();
		}
		return $this->_groupToInterceptMethodAndTypeMap;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasGroupToMethodMap()
	{
		return !empty($this->_groupToInterceptMethodAndTypeMap);
	}
}