<?php
/**
 * This class is meant to extract meaningfull data (data associated to its meaning)
 * from input data fetched from it's parent
 * 
 * @author gui
 *
 */
class G_Miner_Engine_BluePrint_Action_Extract
extends G_Miner_Engine_BluePrint_Action_Abstract
{	
	/**
	 * Final result optional or required
	 * @var unknown_type
	 */
	const FR_OPTIONAL = 213;
	const FR_REQUIRED = 214;
	
	/**
	 * The regex use in the preg_match function
	 * 
	 * @var G_Miner_Engine_Regex_String_Abstract
	 */
	private $_regexStr;
	
	/**
	 * Tells whether the array contained in $_results
	 * must be used for muyltiple Video Entities
	 * (the results must not be erased) completely from
	 * the function: clear(), instead just the first record
	 * must be shifted.
	 * 
	 * @var boolean
	 */
	private $_useMatchAll;
	
	/**
	 * Contains the regex instance from where all results are fetched
	 * 
	 * @var G_Regex
	 */
	private $_regex;
	
	/**
	 * Maps each group of the regex, to an entity
	 * (a final result)
	 * @var unknown_type
	 */
	private $_groupToEntityArray;
	
	/**
	 * Tells if getResult() can be called
	 * 
	 * @var unknown_type
	 */
	private $_isResultReady;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_hasFinalResults = false;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_hasChildAction = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_currentChildAction = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_nextStep = null;
	
	/**
	 * Contains the method wrapper that will
	 * intercept the results
	 * 
	 * @var unknown_type
	 */
	private $_methodWrapper = null;
	
	/**
	 * Allows to avoid intercepting results twice
	 * Stores the intercepted results untill clear is called
	 * 
	 * @var unknown_type
	 */
	private $_interceptedResults = null;
	
	/**
	 * 
	 * @param $regex is the regular expression needed to extract the content from the inputData
	 * @param $useMatchAll
	 * @param $groupNumToEntityArray
	 * @param $nextActionInputDataGroupNumber
	 * @return unknown_type
	 */
	public function __construct($regexStr, $useMatchAll = false)
	{
		parent::__construct();
		if (true === is_string($regexStr)) {
			$regexStr = new G_Regex_String_Generic($regexStr);
		}
		if (!($regexStr instanceof G_Regex_String_Abstract)) {
			throw new G_Miner_Engine_BluePrint_Action_Extract_Exception('Action extract constructor first parameter must be of type string or instance of G_Regex_String_Abstract');
		}
		$this->_regexStr = $regexStr;
		$this->_useMatchAll = (boolean) $useMatchAll;
		//prepare the intercept hook
		$this->_methodInterceptGroupMapping[G_Miner_Engine_BluePrint_Action_Extract_Method_Wrapper::INTERCEPT_TYPE_TOGETHER] = array();
		$this->_methodInterceptGroupMapping[G_Miner_Engine_BluePrint_Action_Extract_Method_Wrapper::INTERCEPT_TYPE_ONEBYONE] = array();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function setMethodWrapper(G_Miner_Engine_BluePrint_Action_Extract_Method_Wrapper $mW)
	{
		$this->_methodWrapper = $mW;
	}
	
	/**
	 * Maps each group in the regex to an entity in the application
	 * ex: array(2=>array(VideoName, self::FR_OPTIONAL, 'explode'), 3=>DateReleased);
	 * 
	 * @param $groupToEntity
	 * @return unknown_type
	 */
	public function setGroupMapping(array $groupToEntityArray)
	{
		//if an empty array is passed, it means there will be just one group
		//and it will be used as input data for the child action
		if ($this->_hasFinalResults = !empty($groupToEntityArray)) {
			$this->_groupToEntityArray = $groupToEntityArray;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see BluePrint/G_Miner_Engine_BluePrint_Action#hasFinalResults()
	 */
	public function hasFinalResults()
	{
		if (null === $this->_hasFinalResults) {
			throw new G_Miner_Engine_BluePrint_Action_Extract_Exception('You must call setGroupMapping() before hasFinalResults()');
		}
		return $this->_hasFinalResults;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see BluePrint/G_Miner_Engine_BluePrint_Action#spit()
	 */
	public function spit()
	{
		if (false === $this->_hasFinalResults) {
			throw new G_Miner_Engine_BluePrint_Action_Extract_Exception('This action has no final results, therefor it cannot spit, call hasFinalResults() before calling spit() to avoid exception.');
		}
		if (false === $this->_isResultReady) {
			throw new G_Miner_Engine_BluePrint_Action_Extract_Exception('The result is not ready, call execute()');
		}
		//get rid of results that are not final in results array (getResults())
		$entityToResult = array();
		$results = $this->getResults();
		//map each entity to its result
		G_Echo::l2("Spitting results: \n");
		G_Echo::l2("Results array after extract and intercept process : \n");
		foreach ($this->_groupToEntityArray as $k => $array) {
			if (isset($results[$array['regexGroup']]) && !empty($results[$array['regexGroup']])) {
				$entityToResult[$array['entity']] = $results[$array['regexGroup']];
			} else if (0 === $array['isOpt']) {
				throw new G_Miner_Engine_BluePrint_Action_Extract_Exception('There is a result that is required and it is missing : ' . print_r($results, true) . ' mapping : ' . print_r($this->_groupToEntityMapping));
			}
		}
		G_Echo::l2("Results array after associating them to entities : ");
		return $entityToResult;
	}
	
	/**
	 * Make regex object contain some results
	 * 1. Has been executed
	 * 		a. is not MatchAll -> throw up (only one execution per result)
	 * 		b. is MatchAll	   -> advance the pointer to the next result so getResult() can return it
	 * 2. Has not been executed
	 * 		a. is MatchAll
	 * 		b. is not MatchAll
	 * 
	 * @note _isResultReady replaces _isExecuted
	 * @return boolean
	 */
	protected function _execute()
	{
		//if has never been executed
		if (false === $this->_isExecuted) {
			G_Echo::l2("first time execute is called\n");
			//create new regex instances
			//PARENT : EXTRACT specify group for input
			if ($this->getParent() instanceof G_Miner_Engine_BluePrint_Action_Extract) {
				G_Echo::l2("parent is of type Extract so get the result by passing the group number\n");
				if (null === $this->_groupForInputData) {
					throw new G_Miner_Engine_BluePrint_Action_Exception('call setGroupForInputData($group), when the parentAction is an instance of G_Miner_Engine_BluePrint_Action_Extract');
				}
				$this->_regex = new G_Regex($this->getParent()->getResult($this->_groupForInputData), $this->_regexStr);
			//PARENT : GETCONTENTS
			} else {
				G_Echo::l2("parent is of type GetContents so get the result without passing the group number\n");
				//no need to specify the group for intput data
				$this->_regex = new G_Regex($this->getParent()->getResult(), $this->_regexStr);
			}
			
			//save last input
			$this->_lastInput = $this->_regex->getInputString();
			G_Echo::l2('input first 50/' . mb_strlen($this->_lastInput) . ' chars are : ' . mb_substr($this->_lastInput, 0, 100));
			G_Echo::l2('regex is : ' . $this->_regexStr->toString());
			
			//call the right function (preg_match / preg_match_all)
			if (true === $this->_useMatchAll) {
				G_Echo::l2("is useMatchAll, call regex->matchAll()\n");
				$this->_isResultReady = (boolean) $this->_regex->matchAll();
			} else {
				G_Echo::l2("is not useMatchAll, call regex->match()\n");
				$this->_isResultReady = (boolean) $this->_regex->match();
			}
			//now it was executed at least once
			$this->_isExecuted = true;
			G_Echo::l2("remember that execute was called at least once\n");
		//else if has already been executed at least once
		} else {
			G_Echo::l2("this Extract instance was already executed once\n");
			//make sure clear() was called between two execute() calls
			if (false === $this->_useMatchAll) {
				throw new G_Miner_Engine_BluePrint_Action_Extract_Exception('You are trying to execute the same action twice whereas it is not useMatchAll call clear()');
			}
			G_Echo::l2("it is use match all and the result points to the right one so result is ready\n");
			$this->_isResultReady = true;
		}
		G_Echo::l2('Input was : ' . print_r($this->_regexStr->getFullRegex(), true));
		//let the calling class know if everything went ok
		return $this->_isResultReady;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see BluePrint/G_Miner_Engine_BluePrint_Action#getResult($groupNumber)
	 */
	public function getResult($groupNumber = null)
	{
		if (null === $groupNumber) {
			throw new G_Miner_Engine_BluePrint_Action_Extract_Exception('The group number cannot be null (getResult() first param)');
		}
		$res = $this->getResults();
		//ensure the group is in the regex result array
		if (!isset($res[$groupNumber])) {
			throw new G_Miner_Engine_BluePrint_Action_Extract_Exception('The group does not exist in regex object');
		}
		//return the specified group
		return $res[$groupNumber];	
	}
	
	/**
	 * 
	 * @param unknown_type $groupNumber
	 * @return unknown_type
	 */
	public function hasGroupNumber($groupNumber)
	{
		//ensure there is a result
		if (false === $this->_isResultReady) {
			throw new G_Miner_Engine_BluePrint_Action_Extract_Exception('You must call execute() before getResult(), or the regex is not valid.');
		}
		return $this->_regex->hasGroupNumber($groupNumber);
	}
	
	/**
	 * 
	 * @param unknown_type $groupNumber
	 * @return unknown_type
	 */
	public function hasResult($groupNumber)
	{
		return $this->hasGroupNumber($groupNumber);
	}
	
	/**
	 * Return all the results
	 * 
	 * @return unknown_type
	 */
	public function getResults()
	{	
		//ensure there is a result
		if (false === $this->_isResultReady) {
			throw new G_Miner_Engine_BluePrint_Action_Extract_Exception('You must call execute() before getResult(), or the regex is not valid.');
		}
		//return all groups of current match, if match all only get current match
		/*
		 * Allow the user to intercept the results and modify them with some method
		 */
		if (null === $this->_interceptedResults) {
			$this->_interceptedResults = $this->_regex->getCurrentMatch();	
			if (null !== $this->_methodWrapper) {
				$this->_interceptedResults = $this->_methodWrapper->intercept($this->_interceptedResults);
			}
		}
		return $this->_interceptedResults;
	}
	
	/**
	 * On one hand clear is used to handle the regex results,
	 * 	in the case it is NOT matchAll : 
	 * 		it will null the regex so a new regex will be generated in execute() with a new input string from the parent result
	 * 	in the case it is matchAll :
	 * 		if there are more matches in stack
	 * 			it will try to point to the next match so it can be returned by getResults
	 * 		if there are no more matches in stack
	 * 			it will null the regex so a new regex will be generated by execute() with a new input string from parent result
	 * On the other hand it handles the child 
	 * 	if it is a stack
	 * 		when there are more childs in stack it will throw an exception by telling the instance cannot be cleared untill all childs have been executed
	 * 		when there are NO more childs in stack it will clear the instance normally and set the shiftedChildActions back to childActions so the can be executed again with new data
	 * 	if it is NOT stack it will clear the instance normally
	 * 
	 * (non-PHPdoc)
	 * @see BluePrint/G_Miner_Engine_BluePrint_Action#clear()
	 */
	protected function _clear()
	{		
		//empty intercepted results so next call will populate them with fresh data
		$this->_interceptedResults = null;
		
		//see if there are more results to be executed
		if (true === $this->_useMatchAll && true === $this->_regex->goToNextMatch()) {
			G_Echo::l2("EXTRACT: MORE_RESULTS_TO_EXECUTE");
			//result is ready
			return G_Miner_Engine_BluePrint_Action_Abstract::MORE_RESULTS_TO_EXECUTE;
		}

		//forbid getResult() to be called before execute()
		//and forbid execute to be called twice without calling clear() in between
		$this->_isResultReady = false;
		$this->_isExecuted = false;
		$this->_regex = null;
		return G_Miner_Engine_BluePrint_Action_Abstract::CANNOT_EXECUTE_AWAITING_INPUT;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Engine/BluePrint/Action/G_Miner_Engine_BluePrint_Action_Abstract#_fullClear()
	 */
	protected function _fullClear()
	{
		$this->_interceptedResults = null;
		$this->_isResultReady = false;
		$this->_isExecuted = false;
		$this->_regex = null;
	}
}