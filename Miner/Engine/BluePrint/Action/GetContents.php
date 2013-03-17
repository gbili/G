<?php
/**
 * Get the contents from a url and
 * converts the output to utf8 if needed
 * 
 * @author gui
 *
 *
 */
class G_Miner_Engine_BluePrint_Action_GetContents
extends G_Miner_Engine_BluePrint_Action_Abstract
{
		
	/**
	 * 
	 * @var array
	 */
	static private $_secondsDelayBetweenRequests = array(10, 25);
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_callbackWrapper = null;

	/**
	 * Contains the input data when the action
	 * is the root actio and it cannot retrieve
	 * the input from another action.
	 * 
	 * Once used, this var is set to false
	 * 
	 * @var string
	 */
	private $_bootstrapInputData = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_result = null;
	
	/**
	 * 
	 * @param allows to set the input data from construction 
	 * (usefull from bluePrint when root action) $urlString
	 * @return unknown_type
	 */
	public function __construct($urlString = null)
	{
		//this is a hack to let the root action take its input from itself
		if (null !== $urlString) {
			$this->_bootstrapInputData = $urlString;
		}
	}
	

	
	/**
	 * 
	 * @param integer $min
	 * @param integer $max
	 * @return unknown_type
	 */
	static public function setSecondsDelayBetweenRequests($min, $max = null)
	{
		if (!is_int($min)) {
			throw new G_Miner_Engine_Blueprint_Action_GetContents_Exception('setSecondsDelay, min parameter must be of type int');
		}
		if (null !== $max) {
			if (!is_int($max)) {
				throw new G_Miner_Engine_Blueprint_Action_GetContents_Exception('setSecondsDelay, max parameter must be of type int');
			}
			if ($min > $max) {
				throw new G_Miner_Engine_Blueprint_Action_GetContents_Exception("max must be at least equal to min or greater given : min $min, max $max");
			}
			self::$_secondsDelayBetweenRequests = array($min, $max);
		} else {
			self::$_secondsDelayBetweenRequests = $min; 
		}
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	static public function getSecondsDelayBetweenRequests()
	{
		return (is_array(self::$_secondsDelayBetweenRequests))? rand(self::$_secondsDelayBetweenRequests[0], self::$_secondsDelayBetweenRequests[1]) : self::$_secondsDelayBetweenRequests;
	}
	
	/**
	 * 
	 * @param G_Miner_Engine_BluePrint_Action_GetContents_Callback_Wrapper $cW
	 * @return unknown_type
	 */
	public function setCallbackWrapper(G_Miner_Engine_BluePrint_Action_GetContents_Callback_Wrapper $cW)
	{
		$this->_callbackWrapper = $cW;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getCallbackWrapper()
	{
		if (null === $this->_callbackWrapper) {
			throw new G_Miner_Engine_Blueprint_Action_GetContents_Exception('The callback handler is not set');
		}
		return $this->_callbackWrapper;
	}
	
	/**
	 * This type of action never have final results
	 * (non-PHPdoc)
	 * @see BluePrint/G_Miner_Engine_BluePrint_Action#hasFinalResults()
	 */
	public function hasFinalResults()
	{
		return false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see BluePrint/G_Miner_Engine_BluePrint_Action#getResult($groupNumber)
	 */
	public function getResult($groupNumber = null)
	{
		if (false === $this->_isExecuted) {
			throw new G_Miner_Engine_BluePrint_Action_GetContents_Exception('You must call execute() before getResult()');
		}
		return $this->_result;
	}
	
	/**
	 * 
	 * @param unknown_type $action
	 * @return unknown_type
	 */
	private function _getInputFromAction($action, $groupNumber)
	{
		$input = null;
		if (!($action instanceof G_Miner_Engine_BluePrint_Action_Extract)) {
			throw new G_Exception("GetContents parent must be of type extract when not root");
		}
		
		if ($action->isExecuted()) {
			//see whether there is a callback used to treat input
			if (null !== $this->_callbackWrapper) {
				$input = $action->getResults();
			//otherwise make sure it is possible directly take input as a string
			} else if (null !== $groupNumber) {
				$input = $action->getResult($groupNumber);	
			} else {
				throw new G_Exception("GetContents action needs a string as input. Array is allowed only when using callback. As it is not the root action you must specify the groupForInputData so it can take a single element from the parent extract element");
			}
		}
		return $input;
	}
	
	/**
	 * The input can come from three different places
	 * When root : 
	 * -boostratInputData
	 * Else :
	 * -other action than parent : $_otherInputActionId & $_otherActionGroupForInputData
	 * -lastInput (in case there is a cw) : $_lastInput 
	 * -parent : $_inputGroupNumber
	 * 
	 * The normal action flow is that the roots gets input from bostrapInputData
	 * then it executes, and the result is made available for the children
	 * Then the child executes and so on.
	 * However there may be cases, where some action will need to take
	 * input from a child action so it can create more results that is when
	 * the flow chages for some loops, until the child cannot generate more
	 * results.
	 * 
	 * @return unknown_type
	 */
	private function _retrieveInput()
	{
		$input = null;
		//if it is the root
		if ($this->isRoot()) {
			if (null === $this->_bootstrapInputData) {//make sure input is available
				throw new G_Exception("It is root and it has no input data when root");
			} else if (false !== $this->_bootstrapInputData) {//make sure it is the first time execute is called
				$ret = $this->_bootstrapInputData;
				$this->_bootstrapInputData = false; //make sure the bootsrap input does not get inputed more than once, after first time, use lastInput
				return $ret;
			}
		}
		//if it still has no input, it means it has already been executed or it is not the root
		//see whether it can take the input from another action than its parent when available
		if (null !== $this->_otherInputActionId) {
			//make sure result is still available
			return $this->_getInputFromAction($this->getBluePrint()->getAction($this->_otherInputActionId), $this->_otherActionGroupForInputData);
			
		}
		//if input is still not available
		//otherwise see if input is available from callback last result or first execution result
		if (null !== $this->_lastInput && null !== $this->_callbackWrapper) {
			return $this->_lastInput;
		} else if (! $this->isRoot()) { //otherwise input must be taken from parent
			return $this->_getInputFromAction($this->getParent(), $this->_groupForInputData);
		} else {
			throw new G_Exception('The action could not retrieve its content from any of the options available');
		}
	}
	
	/**
	 * This will allow the engine to inspect the
	 * actions last input
	 * 
	 * @return unknown_type
	 */
	public function getInput()
	{
		return $this->_retrieveInput();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	protected function _execute()
	{
		if (true === $this->_isExecuted) {
			throw new G_Exception("call clear before reexecuting");
		}
		
		//let the engine know that it needs to execute this action
		//again, once the result is avaiable from child
		if (null !== $this->_otherInputActionId) {
			$this->_message = G_Miner_Engine::AWAITING_CHILD_RESULT;
		}

		$input = $this->_retrieveInput();

		//allow for callback to intercept the input
		if (null !== $this->_callbackWrapper) {
			if (!$this->_callbackWrapper->hasMoreLoops()) {
				throw new G_Exception("loop reached end cannot execute anymore, call clear()");
			}
			G_Echo::l2('applying callback to input' . "\n");
			G_Echo::l2("input is $input \n");
			$input = $this->_callbackWrapper->apply($input);
			G_Echo::l2("input after callback apply is $input \n");
			G_Echo::l2('saving callback result for next execute() call' . "\n");
			//remember the last output of callback so it does not output the same result everytime
			//note that calling callback->rewindLoop in this case will directly set the loop to end
			//on next call because the input wont set itself back to the first state
		}
		$this->_lastInput = $input;

		//generate the result
		G_Echo::l2("create the url with input : $input \n");
		$url = new G_Url($input);
		if (!$url->isValid()) {
			throw new G_Exception("the url string is not valid given : " . print_r($url));
		}
		
		//obfuscation
		$this->_applyDelay();
		
		//save last input so it is available when some action crashes, this will help the engine start next time
		$this->_result = @file_get_contents($url->toString());
		if (false === $this->_result) {
			throw new G_Miner_Engine_BluePrint_Action_GetContents_Exception('file_get_contents() did not succeed, url : ' . print_r($url->toString(), true));
		}
		G_Echo::l2('adjusting charset to utf8 if needed, current result charset : ' . G_Encoding::detectEncoding($this->_result) . "\n");
		$this->_result = G_Encoding::utf8Encode($this->_result);
		print_r(mb_substr(htmlentities($this->_result), 0, 50));
		G_Echo::l2('execution succeed' . "\n");
		return $this->_isExecuted = true;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see BluePrint/G_Miner_Engine_BluePrint_Action#clear()
	 */
	protected function _clear()
	{
		G_Echo::l2("GET_CONTENTS : clear() \n");
		
		$this->_isExecuted = false;
		$this->_result = null;
		
		G_Echo::l2("GET_CONTENTS : has callback wrapper? : ");
		//if callback is being used
		if (null !== $this->_callbackWrapper) {
			G_Echo::l2("yes! \n");
			//skip this check if full clear
			//and from the same input, it can create more results
			G_Echo::l2("GET_CONTENTS : wrapper has more loops? : ");
			if ($this->_callbackWrapper->hasMoreLoops()) {
				G_Echo::l2("yes! \n");
				return G_Miner_Engine_BluePrint_Action_Abstract::MORE_RESULTS_TO_EXECUTE;//tell engine to call execute again.
			}
			G_Echo::l2("no! \n");
			G_Echo::l2("GET_CONTENTS : action is root? : ");
			//otherwise if it cannot create more results from the same input
			if (!$this->isRoot()) {//and it is not root
				G_Echo::l2("no! so rewind loop and clear input data so it can get new input from parent next time \n");
				$this->_callbackWrapper->rewindLoop();//rewind the loop and wait for new input
				//now, inputDataWhenRoot can also be used as a placeholder for callback output
				//as it is allways used as input when not null, make sure it is null so on next
				//execution the input will be taken from the parent new result
				$this->_bootstrapInputData = null;//empty placeholder
			}
		}
		G_Echo::l2("no! \n");
		G_Echo::l2("GET_CONTENTS : cannot execute waiting parent execute\n");
		//and tell the parent to execute parent
		return G_Miner_Engine_BluePrint_Action_Abstract::CANNOT_EXECUTE_AWAITING_INPUT;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Engine/BluePrint/Action/G_Miner_Engine_BluePrint_Action_Abstract#_fullClear()
	 */
	protected function _fullClear()
	{
		$this->_isExecuted = false;
		$this->_result = null;
		if (null !== $this->_callbackWrapper) {
			$this->_callbackWrapper->rewindLoop();
			$this->_bootstrapInputData = null;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see BluePrint/G_Miner_Engine_BluePrint_Action#spit()
	 */
	public function spit()
	{
		throw new G_Miner_Engine_BluePrint_Action_GetContents_Exception('GetContents actions never have final results, don\'t call spit().');
	}
	
	/**
	 * This helps in obfuscation
	 * 
	 * @return unknown_type
	 */
	private function _applyDelay()
	{
		$delay = self::getSecondsDelayBetweenRequests();
		G_Echo::l2("delaying request for $delay seconds");
		for ($i = 0; $i < $delay; $i++) {
			sleep(1);
			G_Echo::l2('.');
		}
		G_Echo::l2("\n");
	}
}