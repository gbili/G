<?php
/**
 * @todo update graphic, current deprecated                                               
 *                                    __________________________ 
 *                                    @ G_Miner_Engine_BluePrint @
 *                                    @_________________________@
 *                                      ^           |
 *       ____________________________   |   ________|_________________________
 *       %                          %   |   @                                 @
 *       % G_Db_Req 				%  (1)  @  G_Miner_Engine_BluePrint_Action_Abstract@ (chain)
 *       %__________________________%   |   @_________________________________@
 *                ^                     |           |
 *                |                     |          (2)
 *              save                    |           |
 *                |                    _|___________v__________________
 *                |______(8)__loop____@                                @
 *                                    @                                @
 *       	                          @          G_Miner_Engine       @                   wwww content (not understandable by application)
 *      __________________            @                                @
 *      @ currentInstance @           @                                @
 *      @________________ @           @                                @
 *             ^                      @_________________loop___________@
 *             |                          |              ^    ^
 *         finalResults             @ currentInstance @  |    |           
 *        	  (7)                     finalResults       |    |           		
 *        _____|__________________        |              | finalResults   
 *        @                       @      (6)            (3)   |          
 *        @ G_Miner_Engine_Lexer @<______|              |   (5)?
 *        @_______________________@         _____________v____|_____________
 *       								   @ G_Miner_Engine_BluePrint_Action_Abstract@<---(4)->Retrieve content
 * 								           @________________________________@
 * 
 * The G_Miner_Engine is in charge of instantiating a (1) G_Miner_Engine_BluePrint
 * then it will retrieve it's (2) G_Miner_Engine_BluePrint_Action_Abstractinstances
 * chain and from there it will start the dumping process. (3)
 * Every time a G_Miner_Engine_BluePrint_Action_Abstracthas final results (5)
 * the G_Miner_Engine will get an instance of a G_Miner_Engine_Lexer_Abstract subclass (6)
 * from the G_Miner_Engine_Lexer_Registry, and pass the G_Miner_Engine_BluePrint_Action_Abstract
 * final results along with the current instance being populated 
 * to G_Miner_Engine_Lexer_Abstract->populateInstance(:instance, :finalResults)
 * 
 * From there the G_Miner_Engine_Lexer_Abstract subclass should know how to handle the final results. (7)
 * @see G_Miner_Engine_Lexer_Abstract (roughly each final result is associated to a term, and each
 * term triggers an instance setter method, the lexer can be thought as a dictionary where each
 * word triggers a method)
 * 
 * Once the instance is considered full (there are no more actions for the current instance)
 * then a new instance is created from G_Miner_Engine::$instancesClassName
 * 
 * Once G_Miner_Engine::$numOfInstancesInStackBeforeSave count is reached, then all the
 * instances in stack G_Miner_Engine::_instancesArray are saved by calling the method save()
 * on each one, that is why they must implement G_Db_Buttons_Interface (8)
 * 
 * 
 * 
 * To instantiate G_Miner_Engine (from now on : Engine), you must provide a host as a string that
 * it will use to create an instance of G_Url_Authority_Host (cleaner code) or directly as an 
 * instance (you will write more code for nothing). With that host the engine tries to create a 
 * G_Miner_Engine_BluePrint instance, whose contents you will have previously created and saved
 * with the help of G_Miner_Engine_BluePrint_Savable.
 * @see G_Miner_Engine_BluePrint_Savable to learn how create Engine useable bluePrints
 * 
 * 
 * 
 * 
 * 
 * 
 * @author gui
 *
 */
class G_Miner_Engine
{	
	const REACHED_LEAF = 10;
	const END_OF_BLUEPRINT_EXECUTION = 11;
	const ACTION_EXECUTION_SUCCEED = 12;
	const NEXT_ACTION_IS_READY_TO_EXECUTE = 13;
	const SKIP_LEAF = 14;
	const AWAITING_CHILD_RESULT = 15;
	const CHANGE_FLOW = 17;
	
	/**
	 * Try catch exceptions when the instance throws one
	 * 
	 * @var unknown_type
	 */
	static public $skipToNextInstanceWhenExceptionOccurs = true;
	
	/**
	 * When $_exceptionSuccessDifferenceCount reaches this
	 * number, the try catch block exits the script this means:
	 * Allow $maxExceptionsCatchPerSuccessNumber of exceptions
	 * + number of successes, before exiting the script.
	 * 
	 * @var unknown_type
	 */
	static public $maxExceptionsCatchPerSuccessNumber = 5;
	
	/**
	 * Exceptions add 1 to count
	 * Successes rest 1 to count
	 * If exceptionSuccessFailDifferenceCount >= self::$maxExceptionCatchPerSuccessNumber
	 * exit the script
	 * 
	 * @var unknown_type
	 */
	private $_numberOfRemainingFailsAllowed = 5;
	
	/**
	 * G_Miner_Engine_Lexer_Abstract instance
	 * will populate instances of this type
	 * The class must implement G_Db_Buttons_Interface
	 * 
	 * @var string
	 */
	static public $_instancesClassName = 'G_Vid_Savable';
	
	/**
	 * Contains the class instances that are created from the results
	 * 
	 * @var array
	 */
	private $_instancesArray;
	
	/**
	 * Is the instance that will be filled by the lexer
	 * 
	 * @var determined in self::$instancesClassName
	 */
	private $_currentInstance;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_bluePrint;
	
	/**
	 * When actions spit content before the newInstanceGeneratingPoint
	 * has been executed, the currentInstance does not exist. Thereafter
	 * the content must be spit to the lexer bucket, so it can be injected
	 * once the instance is available.
	 * Once the nIGPAction has been executed, the currentAction is allways
	 * available, so the only way to set this right is to set it before
	 * nIGPAction gets executed the first time
	 * 
	 * @var unknown_type
	 */
	private $_actionsNeedingToSpitToLexerBucket = array();
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_numOfInstancesInStackCount;
	
	/**
	 * SAS = skip and switch
	 * @var unknown_type
	 */
	static private $_numOfInstancesBeforeSASToBrother = 0;
	
	/**
	 * Deepnes control action id
	 * when engine executes this action it will
	 * check for numOfInstancesBeforeSasCount, and
	 * if it is = 0 then it will skip exec and switch to brother
	 * @var unknown_type
	 */
	static private $_sASActionId = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_numOfInstancesBeforeSASCount = 0;
	
	/**
	 * @var unknown_type
	 */
	private $_currentAction = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_nIGPLastInput = array();
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_errorTriggerActionId = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_actionIdAwaitingActionIdResult = array();
	
	/**
	 * Action id => result
	 * @var unknown_type
	 */
	private $_actionResultForAwaitingActions = array();
	
	/**
	 * 
	 * @var unknown_type
	 */
	static private $_numOfInstancesInStackBeforeSave = 1;
	
	/**
	 * Character set of the site being dumped
	 * 
	 * @var unknown_type
	 */
	static private $_inputCharSet;
	
	/**
	 *
	 * @var G_Miner_Engine_Lexer_Abstract
	 */
	private $_lexerInstance = null;
	
	/**
	 * Instanciate the blue print
	 * 
	 * @param G_Url $url
	 * @return unknown_type
	 */
	public function __construct($host)
	{
		if (is_string($host)) {
			$host = new G_Url_Authority_Host($host);
		} else if ($host instanceof G_Url_Authority_Host) {
			throw new G_Miner_Engine_Exception('constructor first param must be a valid url string or a G_Url_Authority_Host instance');
		}
		$bluePrint = new G_Miner_Engine_BluePrint($host);
		if (!($bluePrint instanceof G_Miner_Engine_BluePrint)) {
			throw new G_Miner_Engine_Exception('Cannot create BluePrint for given authority : ' . print_r($host->toString(), true));
		}
		$this->_bluePrint = $bluePrint;
		
		$this->_numOfInstancesInStackCount = 0;
	}
	
	/**
	 * 
	 * @param unknown_type $secs
	 * @return unknown_type
	 */
	static public function setExecTimeLimit($secs)
	{
		set_time_limit((integer) $secs);
	}
	
	/**
	 * 
	 * @param unknown_type $secs
	 * @return unknown_type
	 */
	static public function setHttpRequestTimeInterval($minSecs, $maxSecs = null)
	{
		G_Miner_Engine_BluePrint_Action_GetContents::setSecondsDelayBetweenRequests($minSecs, $maxSecs);
	}
	
	/**
	 * This will make the thread skip and switch
	 * to brother execution even if more results can
	 * be generated, when the number of instances
	 * specified in param have been created
	 * 
	 * It allows to set a dumping deepness
	 * 
	 * @param unknown_type $number
	 * @return unknown_type
	 */
	static public function setNumOfInstancesBeforeSAS($number)
	{
		self::$_numOfInstancesBeforeSASToBrother = (integer) $number;
	}
	
	/**
	 * 
	 * @param unknown_type $actionId
	 * @return unknown_type
	 */
	static public function setSASActionId($actionId)
	{
		self::$_sASActionId = (integer) $actionId;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	static public function getInputCharSet()
	{
		return self::$_inputCharSet;
	}
	
	/**
	 * 
	 * @param unknown_type $str
	 * @return unknown_type
	 */
	static public function setInputCharSet($str)
	{
		self::$_inputCharSet = mb_strtoupper((string) $str);
		//also set associated classes char set
		G_Slug::setInputCharSet(self::$_inputCharSet);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getBluePrint()
	{
		return $this->_bluePrint;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getLexerInstance()
	{
		if (null === $this->_lexerInstance) {
			$this->_lexerInstance = G_Miner_Engine_Lexer_Registry::getInstance(self::$_instancesClassName);
		}
		return $this->_lexerInstance;
	}
	
	/**
	 * Some actions retrieve their input from child
	 * But as the execution goes from parent to child,
	 * their (second) input may not be available. That
	 * is why they are added to a stack of actions that
	 * await anothers action input.
	 * Once that awaited action is executed its result
	 * will be remembered and injected to the actoins
	 * that are in this stack.
	 * 
	 * @return unknown_type
	 */
	private function _addActionIdToActionsAwaitingResult(G_Miner_Engine_Thread $t)
	{
		$this->_actionIdAwaitingActionIdResult[$t->getAction()->getId()] = $t->getAction()->getOtherInputActionId();
	}
	
	/**
	 * Some actions result is used as input for other actions
	 * When it is the case, a record is created in _actionIdAwaitingActionIdResult
	 * so the result of the action can be inputed to those actions
	 * when the moment comes (when they are asked to execute again)
	 * 
	 * @param G_Miner_Engine_Thread $t
	 * @return unknown_type
	 */
	private function _rememberActionResultForAwaitingActions(G_Miner_Engine_Thread $t)
	{
		$this->_actionResultForAwaitingActions[$t->getAction()->getId()] = $t->getAction()->getResult();
	}
	
	/**
	 * 
	 * @param unknown_type $actionId
	 * @return unknown_type
	 */
	private function _getActionRememberedResult($actionId)
	{
		if (!isset($this->_actionResultForAwaitingActions[$actionId])) {
			throw new G_Miner_Engine_Exception('There is an action awaiting input from another action, and it is not available');
		}
		return $this->_actionResultForAwaitingActions[$actionId];
	}
	
	/**
	 * 
	 * @param unknown_type $t->getAction()Id
	 * @return unknown_type
	 */
	private function _hasActionsAwaitingResult($actionId)
	{
		return in_array($actionId, $this->_actionIdAwaitingActionIdResult);
	}
	
	/**
	 * 
	 * @param unknown_type $actionId
	 * @return unknown_type
	 */
	private function _isAwaitingActionResult($actionId)
	{
		return isset($this->_actionIdAwaitingActionIdResult[$actionId]);
	}
	
	/**
	 * Actions are allowed to send messages to engine
	 * in order to make him chage the normal flow
	 * 
	 * @return unknown_type
	 */
	private function _recieveMessage(G_Miner_Engine_Thread $t)
	{
		$message = $t->getAction()->getMessage();
		if (self::AWAINTING_CHILD_RESULT === $message) {
			$this->_addActionIdToActionsAwaitingResult($t);
		}
	}
	
	
	/**
	 * 
	 * @return unknown_type
	 */
	private function _preExecute()
	{
		
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	private function _postExecute()
	{
		
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	private function _preClear(G_Miner_Engine_Thread $t)
	{
		//handle a possible AWAITING_CHILD_RESULT
		if ($this->_hasActionsAwaitingResult($t->getAction()->getId())) {
			/*foreach ($this->_actionIdAwaitingActionIdResult[$t->getAction()] as $action) {
				$action->injectInput($t->getAction()->getResults());
				$thread = new G_Miner_Engine_Thread($action);
				$this->start($thread);//start a new thread with injected input
			}*/
			$this->_rememberActionResultForAwaitingActions($t);
		}
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	private function _postClear(G_Miner_Engine_Thread $t)
	{
		if ($this->_isAwaitingActionResult($t->getAction()->getId())) {
			$t->getAction()->injectInput($this->_getActionRememberedResult($t->getAction()->getOtherInputActionId()));
			return self::CHANGE_FLOW;
		}
	}
	
	/**
	 * Start calls _executeAllResults
	 * So what it does is, to execute all results
	 * generated by an action.
	 * But the call to _executeResultsAndBrothers(), makes
	 * it execute every result towards leaf before
	 * goin to the next (child || result)
	 * 
	 * Once execute towards leaf returns REACHED_LEAF
	 * then the action with more (childs || results)
	 * closest to the leaf node is goint to be placed
	 * by : -_clearAndPlaceNEA()
	 * (there is also a case where execution 
	 * fail changes the _currentAction
	 * @see _clearAndPlaceNIGPA())
	 * 
	 * as _currentAction and be executed
	 * towards leaf again etc.
	 * @see _executeTowardsLeaf()
	 * 
	 * 
	 * @return unknown_type
	 */
	public function start(G_Miner_Engine_Thread $t = null)
	{
		G_Echo::l2("Method Call : Engine::start()\n");
		if (null === $t) {//default start
			$t = new G_Miner_Engine_Thread($this->getBluePrint()->getRoot());
		}
		G_Echo::l2('if SAS is used to control dumping deepness then check if sASActionId exists in blue print');
		if (null !== self::$_sASActionId) {
			$this->getBluePrint()->getAction(self::$_sASActionId);//throws up if does not exist
		}
		$this->_executeResultsAndBrothers($t);
		G_Echo::l1("END_OF_BLUEPRINT_EXECUTION\n");
		G_Echo::l2("save remaining instances \n");
		$this->_saveInstances();
	}
	
	/**
	 * This function calls _executeTowardsLeaf()
	 * and once the leaf has been reached the
	 * _clearAndPlaceNEA will set _currentAction
	 * to the leaf action's brother, oncle, grand oncle....
	 * which will then execute towards leaf again.
	 * 
	 * 
	 * @return unknown_type
	 */
	private function _executeResultsAndBrothers(G_Miner_Engine_Thread $t)
	{
		//execute towards leaf until it reaches it
		while (true !== $this->_executeTowardsLeaf($t) && self::REACHED_LEAF === $t->getStatus()) {
			//reached leaf, place the next executable action into thread so it is executed towards leaf again
			if (true !== $this->_clearAndPlaceNEA($t) && self::NEXT_ACTION_IS_READY_TO_EXECUTE !== $t->getStatus()) {//roll back
				//if the next executable action, is not ready to execute return
				return;
			}
		}
	}

	/**
	 * This executes an action, gets its
	 * child and executes it etc.
	 * The goal is to execute its child
	 * child before its child brothers per say
	 * 
	 * @return unknown_type
	 */
	private function _executeTowardsLeaf(G_Miner_Engine_Thread $t)
	{
		G_Echo::l2("Method Call : Engine::_executeTowardsLeaf()\n");
		do {
			//try to execute action, if it fails rolback to next
			$this->_executeCurrentAction($t);
			//action that can be executed and execute it
			if ($t->getStatus() === self::END_OF_BLUEPRINT_EXECUTION) {
				G_Echo::l2("Method Return : Engine::_executeTowardsLeaf() : END_OF_BLUEPRINT_EXECUTION\n");
				$t->setStatus(self::END_OF_BLUEPRINT_EXECUTION);
				return;
			} else if (self::SKIP_LEAF === $t->getStatus()) {
				break;//skipping leaf
			}
			//intercept action messages
			if (true === $t->getAction()->hasMessage()) {
				$this->_recieveMessage($t);
			}
			//internally it will check whether it has data useful for application
			if (true === $t->getAction()->hasFinalResults()) {
				G_Echo::l1("has final result\n");
				//if it has, it will return it as an associative array
				$this->_keepResults($t);
			}
			//remeber the last action for when we get out of the loop
			//while there are more child actions (serially)
			G_Echo::l1("trying to get child action for execution\n");
		} while ($t->getAction()->isReadyToReturnChild() && (true !== $t->setAction($t->getAction()->getChild())));
		G_Echo::l2("Method Return : Engine::_executeTowardsLeaf() : REACHED_LEAF\n");
		$t->setStatus(self::REACHED_LEAF);
	}
	
	/**
	 * 
	 * @param G_Miner_Engine_Thread $t
	 * @return unknown_type
	 */
	private function _keepResults(G_Miner_Engine_Thread $t)
	{
		//make sure there is an instance
		if (null === $this->_currentInstance) {
			//if not, the content will be spit to the lexer bucket
			$this->_actionsNeedingToSpitToLexerBucket[] = $t->getAction();
		}
		//check if need to spit to lexer bucket
		if (false !== array_search($t->getAction(), $this->_actionsNeedingToSpitToLexerBucket, true)) {
			//otherwise use the lexer bucket;
			G_Echo::l1("storing data in lexer bucket\n");
			$this->getLexerInstance()->storeInBucket($t->getAction()->spit());
		} else {
			$this->getLexerInstance()->populateInstance($this->_currentInstance, $t->getAction()->spit());
		}
	}
	
	/**
	 * This will try to execute currentAction
	 * and will return self::ACTION_EXECUTION_SUCCEED on success...
	 * 
	 * If current action execution fails, it
	 * will ask _clearActionAndFindNextExecutableAction() to put
	 * the next executable action in currentAction and try to
	 * execute it by calling itself recursively.
	 * 
	 * It will return self::END_OF_BLUEPRINT_EXECUTION
	 * when the currentAction is the root and it 
	 * fails to execute.
	 * 
	 * @param unknown_type $currentAction
	 * @return unknown_type
	 */
	private function _executeCurrentAction(G_Miner_Engine_Thread $t)
	{
		G_Echo::l2("Method Call : Engine::_executeCurrentAction()\n");
		G_Echo::l2("---------------------------------------------------------\n");
		G_Echo::l1("------------------ ACTION ID : {$t->getAction()->getId()} , TITLE : {$t->getAction()->getTitle()}---------------------\n");
		G_Echo::l1("--------------------" . ($t->getAction() instanceof G_Miner_Engine_BluePrint_Action_Extract)? "EXTRACT" : "GETCONTENTS");	
		G_Echo::l2("--------------------\n");
		G_Echo::l1("------------------- PARENT ID : {$t->getAction()->getParent()->getId()}---------------------\n");
		G_Echo::l2("---------------------------------------------------------\n");
		
		/*
		 * make sure the current instance gets saved
		 * and a new one is created when the current
		 * action is NIGP
		 */
		if (true == $t->getAction()->isNewInstanceGeneratingPoint()) {
			$this->_manageNIGP($t);
		}
		//execute current action
	 	if (true === ($ret = $t->getAction()->execute())) {
	 		G_Echo::l2("Method Return : Engine::_executeCurrentAction() : ACTION_EXECUTION_SUCCEED\n");
	 		$t->setStatus(self::ACTION_EXECUTION_SUCCEED);
	 		return;
	 	} 
	 	$t->setStatus($ret);
		$this->_manageExecutionFailAndPlaceNEA($t);
		G_Echo::l2("Method Return : Engine::_executeCurrentAction() : self::SKIP_LEAF\n");
		$t->setStatus(self::SKIP_LEAF);
	}
	
	/**
	 * When an action fails to execute or simply does not
	 * execute, there are no results.
	 * The nature of the action execution flow, makes it
	 * impossible for childs to execute as there would be
	 * no input for them to execute on.
	 * 
	 * That is why there is a need to skip the entire
	 * branching of the current action.
	 * 
	 * So what we do is full a clear() (only if it was executed)
	 * to make it look as comming out of blue print.
	 * 
	 * And then we put the the action from which we want
	 * the flow to go again in $this->_currentAction
	 * and return self::SKIP_LEAF; which will
	 * tell execute towards leaf that it should not try
	 * to spit any output to _currentInstance, but
	 * simply stop executing towards leaf.
	 * Then it will pass the flow to start(), that will
	 * skip the branching and restart the flow from
	 * the action that we placed in _currentAction();
	 * 
	 * NEA = NextExecutableAction
	 * 
	 * @return unknown_type
	 */
	private function _manageExecutionFailAndPlaceNEA(G_Miner_Engine_Thread $t)
	{
		G_Echo::l2("Method Call : Engine::_manageExecutionFail() : \n");
		//store error trigger actionId will be used in _saveNIGPData
		$this->_errorTriggerActionId = $t->getAction()->getId();
	 	G_Echo::l1("current action did not generate any result, id : $this->_errorTriggerActionId");
		if (false === $t->getStatus()) {
	 		G_Echo::l1("it was executed, without success, clear(),");
	 		$t->getAction()->clear();
	 		if ($t->getAction()->isOptional()) {
	 			G_Echo::l1("but it is optional, retake flow from parent\n");
	 			$t->setAction($t->getAction()->getParent());
	 		} else {
	 			G_Echo::l1("and it is not optional, rollback to NIGP action\n");
	 			$this->_clearAndPlaceNIGPA($t);
	 		}
	 	} else if (G_Miner_Engine_BluePrint_Action_Abstract::NO_INPUT_IS_OPT_NO_EXEC === $t->getStatus()) {
			"it is opt and it was not executed, so no clear() is needed, retake flow from parent\n";
	 		$t->setAction($t->getAction()->getParent());
		}
		G_Echo::l2("Method Return : Engine::_manageExecutionFail() : void\n");
	}
	
	/**
	 * 
	 * NIGP = New Instance Generating Point 
	 * @return unknown_type
	 */
	private function _clearAndPlaceNIGPA(G_Miner_Engine_Thread $t)
	{
		G_Echo::l2("Method Call : Engine::_clearAndPlaceNIGPA()\n");
		while (!$t->getAction()->getParent()->isNewInstanceGeneratingPoint() && !$t->getAction()->getParent()->isRoot()) {
			$this->_preClear($t);
			G_Echo::l1("rolling back to new instance generating point last executed child");
			$t->setAction($t->getAction()->getParent());;
		}
		G_Echo::l1("FOUND new instance generating point last executed child");
		G_Echo::l1("clearing Offspring\n");
		$this->_preClear($t);
		$t->getAction()->clearMeAndOffspring();
		G_Echo::l1("Placing new instance generating point action in current thread");
		$t->setAction($t->getAction()->getParent());//should ne nIGP
		if (!$t->getAction()->isNewInstanceGeneratingPoint()) {
			G_Echo::l1("WHOOOPSY current action is not new instance generating point wierd!!!");
			throw new G_Miner_Engine_Exception('wtf, roll back did not work as expected');
		}
		G_Echo::l2("Method Return : Engine::_clearAndPlaceNIGPA() : void");
		$this->_nIGPLastInput = array('id' => $t->getAction()->getId(),
									  'data' => $t->getAction()->getLastInput());
	}
	
	/**
	 * This will clear current action and
	 * place the next executable action in
	 * $this->_currentAction. It will
	 * return self::CURRENT_ACTION_IS_READY_TO_EXECUTE
	 * to let caller know that it can execute
	 * currentAction.
	 * 
	 * If some action's clear returns 
	 * CANNOT_EXECUTE_AWAITING_INPUT
	 * it will clear the parent action (by 
	 * calling itself recursively) until
	 * some action can execute or there are
	 * no more actions to execut in bluePrint
	 * in which case it will return 
	 * self::END_OF_BLUEPRINT_EXECUTION
	 * 
	 * NEA= next executable action
	 * @return unknown_type
	 */
	private function _clearAndPlaceNEA(G_Miner_Engine_Thread $t)
	{
		$this->_preClear($t);
		G_Echo::l2("Method Call : Engine::_clearAndPlaceNEA(actionId:{$t->getAction()->getId()})\n");
		$ret = $t->getAction()->clear();
		if ($ret === G_Miner_Engine_BluePrint_Action_Abstract::MORE_CHILDREN_TO_EXECUTE) {
			G_Echo::l1("MORE_CHILDREN_TO_EXECUTE : the current action needs more execution towards leaf\n");
			$t->setAction($t->getAction()->getChild());
		} else if ($ret === G_Miner_Engine_BluePrint_Action_Abstract::MORE_RESULTS_TO_EXECUTE) {
			G_Echo::l1("MORE_RESULTS_TO_EXECUTE : nothing to do, next call to _currentAction->execute() will execute on next result \n");
		} else if ($ret === G_Miner_Engine_BluePrint_Action_Abstract::CANNOT_EXECUTE_AWAITING_INPUT) {
			G_Echo::l1("CANNOT_EXECUTE_AWAITING_INPUT :no more execution for this action\n");
			if (self::CHANGE_FLOW !== $this->_postClear($t)) {
				if ($t->getAction()->isRoot()) {
					G_Echo::l1("ROOT : we reached the root while rolling back and its clear method returned cannot exec so no more executions possible.\n");
					G_Echo::l2("Method Return : Engine::_clearAndPlaceNEA() : END_OF_BLUEPRINT_EXECUTION\n");
					$t->setStatus(self::END_OF_BLUEPRINT_EXECUTION);
					return;
				} else {
					G_Echo::l1("stepping one action towards root\n");
					$t->setAction($t->getAction()->getParent());
					//this will clear current action as required between each execution
					G_Echo::l2("Method Return : Engine::_clearAndPlaceNEA() : call _clearAndPlaceNEA()\n");
					$this->_clearAndPlaceNEA($t);
					return;
				}
			}
		} else {
			throw new G_Miner_Engine_Exception('clear() action method returned unsupported result');
		}
		G_Echo::l2("Method Return : Engine::_clearAndPlaceNEA() : NEXT_ACTION_IS_READY_TO_EXECUTE\n");
		$t->setStatus(self::NEXT_ACTION_IS_READY_TO_EXECUTE);
	}
	
	/**
	 * This will create and save instances
	 * when needed
	 * 
	 * NIGP : new instance generating point
	 * 
	 * @return unknown_type
	 */
	private function _manageNIGP(G_Miner_Engine_Thread $t)
	{
		G_Echo::l2("Method Call : Engine::_manageNIGP()\n");
		G_Echo::l1("it is new instance gen point\n");
		//save instances when the stack has reached a certain ceil
		if ($this->_numOfInstancesInStackCount >= self::$_numOfInstancesInStackBeforeSave) {
			G_Echo::l1("num of instances in stack count > num before save\n");
			$this->_saveInstances();
		}
		
		//if using dumping deepness control
		if (0 !== self::$_numOfInstancesBeforeSASToBrother) {
			if (null === self::$_sASActionId) {
				throw new G_Miner_Engine_Exception('you must set the SAS action id if you want to use the dumping deepness control feature');
			}
			//the deepness level has been reached
			if(self::$_numOfInstancesBeforeSASToBrother <= $this->_numOfInstancesBeforeSASCount) {
				$t->setAction($this->getBluePrint()->getAction((integer)self::$_sASActionId));//this will clear all offspring from action with id
				$t->setStatus($t->getAction()->clearMeAndOffspring(false));//this will clear action with id and place it as next executable action in thread (with satuts)
				$this->_numOfInstancesBeforeSASCount = 0;//set deepness count to 0
				G_Echo::l1('Using dumping deepness control throug SAS!');
			}

			//count the instance being generated in _manageNIGP
			$this->_numOfInstancesBeforeSASCount++;
		}
		
		G_Echo::l1("creating new instance\n");
		$this->_currentInstance = new self::$_instancesClassName();
		//ensure the instance can be saved (it implements method save())
		if (!($this->_currentInstance instanceof G_Db_Buttons_Interface)) {
			throw new G_Miner_Engine_Exception('G_Miner_Engine::$_instancesClassName class must implement G_Db_Buttons_Interface. ' . print_r(self::$_instancesClassName, true) . ' does not implement it.');
		}
		//add it to stack
		G_Echo::l1("add instance to instances array for posterior save\n");
		$this->_instancesArray[] = $this->_currentInstance;
		$this->_numOfInstancesInStackCount++;
		G_Echo::l2("Method Return : Engine::_manageNIGP() : void\n");
		
		//spit lexer bucket content if there is one
		$this->getLexerInstance()->injectBucketContent($this->_currentInstance);
	}
	
	/**
	 * Save the instances generated during dumping process to the database
	 * 
	 * @return unknown_type
	 */
	private function _saveInstances()
	{
		G_Echo::l1("Method Call : Engine::_saveInstances()\n");
		foreach ($this->_instancesArray as $instance) {
			//Handle eventual exceptions?
			if (true === self::$skipToNextInstanceWhenExceptionOccurs) {
				try {//handle eventual exceptions
					//save all instances
					$instance->save();
					$success = true;
				} catch (G_Exception $e) {
					G_Echo::l1("Instance save thorwn exception with message : {$e->getMessage()}, skip instance and save next\n");
					if (0 >= $this->_numberOfRemainingFailsAllowed) {
						G_Echo::l1('Number of allowed fails with no success, exceeded. Number of subsequent fails allowed : ' . self::$maxExceptionsCatchPerSuccessNumber . ". Remaining fails allowed with no success : {$this->_numberOfRemainingFailsAllowed}");
						$this->_saveNIGPData();
						exit($e->getMessage());//the script
					}
					$success = false;
				}
				//keep track of the number of exceptions - sucesses difference
				if (true === $success && self::$maxExceptionsCatchPerSuccessNumber > $this->_numberOfRemainingFailsAllowed) {//decrease when save() succeed (didn't throw up)
					G_Echo::l1("SAVE SUCCES BUILD UP numberOfRemainingFailsAllowed : {$this->_numberOfRemainingFailsAllowed}\n");
					$this->_numberOfRemainingFailsAllowed++;
				} else if (false === $success) { // increase when exception was thrown, save() failed
					G_Echo::l1("SAVE FAIL DECREASE numberOfRemainingFailsAllowed : {$this->_numberOfRemainingFailsAllowed}\n");
					$this->_numberOfRemainingFailsAllowed--;
				}
			//don't handle exceptions
			} else {
				//save all instances
				$instance->save();
			}
		}
		//empty array to avoid saving twice the same instances
		$this->_currentInstance = null;
		$this->_instancesArray = array();
		$this->_numOfInstancesInStackCount = 0;
	}
	
	/**
	 * @todo use this data on next engine launch
	 * 
	 * @return unknown_type
	 */
	private function _saveNIGPData()
	{
		if (empty($this->_nIGPLastInput)) {
			throw new G_Miner_Engine_Exception('The nIGPLastInput array is empty, so cannot save data for next engine start');
		}
		G_Echo::l1("saving nIGP with id :  last input with id : {$this->_nIGPLastInput['id']}, data : {$this->_nIGPLastInput['data']}, error trigger actionId : $this->_errorTriggerActionId");
		if (null === $this->_errorTriggerActionId) {
			$this->_errorTriggerActionId = 0; //default value
		}
		G_Db_Registry::getInstance('G_Miner_Engine_BluePrint_Action_Savable')->saveNIGPLastInputData($this->_nIGPLastInput['id'], $this->_nIGPLastInput['data'], $this->_errorTriggerActionId);
	}
}