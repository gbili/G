<?php
/**
 * This class serves as a wrapper for the G_Miner_Engine_BluePrint_Action_GetContents_Callback_Abstract
 * subclasses.
 * 
 * This instantiates the subclass, sets the input mapping
 * and calls the callback() method and passes it the input
 * 
 * @author gui
 *
 */
class G_Miner_Engine_BluePrint_Action_GetContents_Callback_Wrapper
{
	const TYPE_LOOP = 34;
	const TYPE_REFACTORINPUT = 35;

	/**
	 * Mapps the input groups to a parameter
	 * number in the callback method
	 * 
	 * @var unknown_type
	 */
	private $_paramToGroupArray = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_callbackInstance;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_methodName = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_hasMoreLoops = null;
	
	/**
	 * Instantiates the right class and creates the params
	 * array, that will be passed to the subclass applyCallback() method
	 * 
	 * @param unknown_type $callbackClassName
	 * @param array $paramToGroupArray
	 * @return unknown_type
	 */
	public function __construct(G_Miner_Engine_BluePrint_Action_GetContents_Callback_Abstract $callbackInstance, $methodName)
	{
		$this->_callbackInstance = $callbackInstance;
		$this->_callbackInstance->throwIfMethodNotExists($methodName);
		$this->_methodName = $methodName;
		//initialize loop state
		$this->_callbackInstance->setMethodLoopReachedEndState($methodName, false);
		$this->_callbackInstance->setMethodLoopIsFirstTime($methodName, true);
	}
	
	/**
	 * 
	 * @param array $paramToGroupArray
	 * @return unknown_type
	 */
	public function setParamToGroupMapping(array $paramToGroupArray)
	{
		if (null !== $this->_paramToGroupArray) {
			throw new G_Miner_Engine_BluePrint_Action_GetContents_Callback_Wrapper_Exception("the param to group map array is already set");
		}
		//ensure array is numerically indexed and in order
		ksort($paramToGroupArray);
		if (array_keys($paramToGroupArray) !== range(0, count($paramToGroupArray) - 1)) {
			throw new G_Miner_Engine_BluePrint_Action_GetContents_CallbackMapper_Exception('You must pass a numerically indexed array from 0 to n, given : ' . print_r($paramToGroupArray));
		}
		$this->_paramToGroupArray = $paramToGroupArray;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function apply($input)
	{
		//the callback was already applied and there are no more loops
		if (!$this->hasMoreLoops()) {
			throw new G_Miner_Engine_BluePrint_Action_GetContents_Callback_Wrapper_Exception("The loop in method : $thid->_methodName reached end so cannot call apply anymore"); 
		}
		//create an array of numerically ordered params with the input
		if (is_array($input)) {
			if (null === $this->_paramToGroupArray) {
				throw new G_Miner_Engine_BluePrint_Action_GetContents_Callback_Wrapper_Exception('You must call setParamToGroupMapping($array) before calling apply, when the input is an array');
			}
			$orderedParams = self::puzzleValuesWithKeys($this->_paramToGroupArray, $input);
		} else if (is_string($input)) {
			$orderedParams = array($input); //wrap the input to make it look as the first an only argument for _callback
		} else {
			throw new G_Miner_Engine_BluePrint_Action_GetContents_Callback_Wrapper_Exception('You must either provide an array or a string to apply($input)');
		}
		//pas the ordered params array and call the callback
		$mName = $this->_methodName;
		$callbackResult = $this->_callbackInstance->$mName($orderedParams);
		if (!is_string($callbackResult)) {
			throw new G_Miner_Engine_BluePrint_Action_GetContents_Callback_Wrapper_Exception("The method $this->_methodName() for class : " . print_r(get_class($this->_callbackInstance), true) . ' must return a string. Current return value : ' . print_r($callbackResult, true));
		}
		//the method should be setting its loop state throws otherwise
		$this->_hasMoreLoops = $this->hasMoreLoops();
		return $callbackResult;
	}
	
	/**
	 * Returns the keys of param1 and the values of array2
	 * by coupleing the values of param1 array, to the keys of param2
	 * that are the same 
	 * Overrides the $arrayGroups keys with numerically indexed ones
	 * from 0 to count($arrayGroups) - 1
	 * 
	 * @param unknown_type $arrayGroups
	 * @param unknown_type $arrayValues
	 * @return unknown_type
	 */
	static public function puzzleValuesWithKeys($arrayGroups, $arrayValues)
	{
		//this only works because arrayGroups have been numerically ordered from 0 to count -1
		$orderedParams = array();
		foreach ($arrayGroups as $group) {
			if (!isset($arrayValues[$group])) {
				throw new G_Miner_Engine_BluePrint_Action_GetContents_Callback_Wrapper_Exception('The groups in callbackMapper array dont match the groups in parent results array');
			}
			$orderedParams[] = $arrayValues[$group];
		}
		return $orderedParams;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function rewindLoop()
	{
		$this->_callbackInstance->setMethodLoopReachedEndState($this->_methodName, false);
		$this->__callbackInstance->setMethodLoopIsFirstTime($this->_methodName, true);
	}
	
	/**
	 * this function will allways return true
	 * if you don't set a value to $this->_hasMoreLoops
	 * now it is set when you call apply()
	 * 
	 * @return unknown_type
	 */
	public function hasMoreLoops()
	{
		if (null === $this->_hasMoreLoops) {
			return true;
		}
		return !$this->_callbackInstance->getMethodLoopReachedEndState($this->_methodName);
	}
}