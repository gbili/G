<?php
/**
 * The BluePrint takes a host as constructor parameter and with that it will
 * try to reconstruct a previously saved (with G_Miner_Engine_BluePrint_Savable)
 * action tree. For that it queries the storage (for example the database) using the
 * G_Db_Registry which must return an instanceof G_Miner_Engine_BluePrint_Db_Interface.
 * @see G_Db_Registry
 * The returned instance contains all the information the bluePrint needs to create
 * an action tree. The action tree may contain two types of actions :
 * 1) G_Miner_Engine_BluePrint_Action_Extract
 * 		Extracts bits (parts) of data from the plain text other actions pass to it.
 * 		It takes input either from Extract or GetContents actions.
 * 2) G_Miner_Engine_BluePrint_Action_GetContents
 * 		Gets the text from the web, given a string url.
 * 		It takes its input from root data or Extract actions.
 * The bluePrint constructs this tree
 * 
 * 
 * 
 * @author gui
 *
 */
class G_Miner_Engine_BluePrint
{
	/**
	 * The type of action
	 * G_Miner_Engine_BluePrint_Action_Extract
	 * 
	 * @var integer
	 */
	const ACTION_TYPE_EXTRACT = 12;
	
	/**
	 * The type of action
	 * G_Miner_Engine_BluePrint_Action_GetContents
	 * 
	 * @var integer
	 */
	const ACTION_TYPE_GETCONTENTS = 13;
	
	/**
	 * Contains one action G_Miner_Engine_BluePrint_Action_Abstractfrom
	 * which all other actions are accessible
	 * 
	 * @var unknown_type
	 */
	private $_lastAction;
	
	/**
	 * this is a flat representation of the
	 * actions tree
	 * So it eases access to actions
	 * 
	 * @var unknown_type
	 */
	private $_actionStack = array();
	
	/**
	 * G_Miner_Engine::run(), will generate instances
	 * where the actions results will be inserted.
	 * This tells at which action (from id) G_Miner_Engine_BluePrint
	 * has to call setAsNewInstanceGeneratingPoint()
	 * so G_Miner_Engine knows that when it reaches that
	 * action it has to generate a new instance.
	 * 
	 * @var unknown_type
	 */
	private $_newInstanceGeneratingPointActionId = null;
	
	/**
	 * Every time an action has been successfully added
	 * to the action chain, then this variable takes
	 * the value of its parent it
	 * So it can be remebered for next action, and help
	 * determining, if the next action is brother (has same parent)
	 * or is child
	 * 
	 * @var unknown_type
	 */
	private $_lastParentId = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_currentAction = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_methodClassInstance = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_callbackClassInstance = null;
	
	/**
	 * 
	 * @var G_Url_Authority_Host
	 */
	private $_host;
	
	/**
	 * Will generate the actions chain from the action set
	 * from Db passed as argument
	 * This is called from G_Miner_Engine_BluePrint::factory()
	 * 
	 * @param G_Url_Authority_Host $host
	 * @return unknown_type
	 */
	public function __construct(G_Url_Authority_Host $host)
	{
		$this->_host = $host;
		//get the bluePrint info from db and set it in instance
		$this->_setInfo();
		/*
		 * after calling setInfo() $_callbackClassInstance and 
		 * $_methodClassInstance must be set if available
		 * and there is new instance generating point action id
		 * fetch the Db to get the action set
		 */
		$recordset = G_Db_Registry::getInstance('G_Miner_Engine_BluePrint')
		                                        ->getActionSet($this->_host);
		//ensure there are rows
		if (false === $recordset) {
			throw new G_Miner_Engine_BluePrint_Exception(
			        'There is no action set for this url : ' 
			        . $this->_host->getHost()->toString()
			        );
		}
		
		//create an action per recordset row
		foreach ($recordset as $row) {
			//determine the action type and populate $this->_currentAction
			if ((integer) $row['type'] === self::ACTION_TYPE_EXTRACT) {
				$this->_initActionExtract($row);
			} else if ((integer) $row['type'] === self::ACTION_TYPE_GETCONTENTS) {
				$this->_initActionGetContents($row);
			} else {
				throw new G_Miner_Engine_BluePrint_Exception(
				        'Unsupported action type given : ' 
				        . print_r($row, true)
				        );
			}
			$this->_currentAction->setTitle($row['title']);
			$this->_currentAction->setId($row['actionId']);
			//determine whether the action is the videoEntityStartingPoint
			if ($this->_currentAction->getId() === $this->_newInstanceGeneratingPointActionId) {
				G_Echo::l2("setting new instance generating point \n");
				$this->_currentAction->setAsNewInstanceGeneratingPoint();
			} else {
				G_Echo::l2("is not new instance generating point \n");
			}
			//add to stack
			$this->_actionStack[(integer) $row['actionId']] = $this->_currentAction;
			//add the action to chain and set the input group (if needed)
			$this->_chain((integer) $row['parentId'], $row['inputGroupNum']);
			//pass a reference of the blue print to every action
			$this->_currentAction->setBluePrint($this);
		}
		//ensure the instance starting point action is present
		if (null === $this->_newInstanceGeneratingPointActionId) {
			throw new G_Miner_Engine_BluePrint_Exception(
			        'The actionId specified as video entity starting point is' 
			        . ' not present'
			        );
		}
		//set the injection hook data
		if (false !== ($injectData = G_Db_Registry::getInstance('G_Miner_Engine_BluePrint')
		                                                ->getInjectionData($row['actionId']))) {
			$this->_currentAction()
			->setOtherInputActionInfo($injectData['injectingActionId'],
			         (((integer) $injectData['inputGroup'] === 0)? null : $injectData['inputGroup']));
		}
		
		//now pass the root pointer to the bluePrint
		$this->_lastAction = $this->_currentAction->getRoot();
		//print_r($this->_lastAction->getChild());
	}
	
	/**
	 * Set the method and callback instances
	 * and the new instance generating point action id
	 * 
	 * @return unknown_type
	 */
	private function _setInfo()
	{
		$dbRegObj = G_Db_Registry::getInstance('G_Miner_Engine_BluePrint');
		if (!($dbRegObj instanceof G_Miner_Engine_BluePrint_Db_Interface)) {
			throw new G_Miner_Engine_BluePrint_Exception('The G_Db_Registry must return an instanceof G_Miner_Engine_BluePrint_Db_Interface');
		}
		$bPRecordset = $dbRegObj->getBluePrintInfo($this->_host);
		if (false === $bPRecordset) {
			throw new G_Miner_Engine_BluePrint_Exception('The bluePrint does not exist');
		}
		
		foreach ($bPRecordset as $record) {
			G_Echo::l1($record);
			if (!isset($record['path'])) { //if path is present all other should be too
				break;
			}
			$this->_loadCMClass($record['path'], (integer) $record['pathType'], (integer) $record['classType']);
		}

		//set the new instance generating point action id
		if (0 === (integer) $record['newInstanceGeneratingPointActionId']) {
			throw new G_Miner_Engine_BluePrint_Exception('there must be a newInstanceGeneratingPointActionId');
		}
		$this->_newInstanceGeneratingPointActionId = (integer) $record['newInstanceGeneratingPointActionId'];
		//try to get the instances
		//initialize as false
	}
	
	/**
	 * Instantiate callback and method classes using CM loader
	 * 
	 * @param unknown_type $path
	 * @param unknown_type $pathType
	 * @param unknown_type $classType
	 * @return unknown_type
	 */
	private function _loadCMClass($path, $pathType, $classType)
	{
		//hack for base path to try to load both class types
		if (G_Miner_Engine_BluePrint_Action_CMLoader::PATH_TYPE_BASE === $pathType) {
			$classTypes = array(G_Miner_Engine_BluePrint_Action_CMLoader::CLASS_TYPE_CALLBACK,
							    G_Miner_Engine_BluePrint_Action_CMLoader::CLASS_TYPE_METHOD);
		} else {
			$classTypes = array($classType);
		}

		foreach ($classTypes as $classType) {
			$className = G_Miner_Engine_BluePrint_Action_CMLoader::loadCMClass($path, $this->_host, $pathType, $classType);
			if (is_string($className)) {
				if (G_Miner_Engine_BluePrint_Action_CMLoader::CLASS_TYPE_CALLBACK === $classType) {
					$this->_callbackClassInstance = new $className();
				} else {
					$this->_methodClassInstance = new $className();
				}
			} else {
				G_Echo::l2("class not loaded : " . print_r(G_Miner_Engine_BluePrint_Action_CMLoader::getErrors(), true));
			}
		}
	}
	
	/**
	 * This function creates an action instance that it makes
	 * available to the constructor by setting $this->_currentAction
	 * 
	 * @param array $info
	 * @return unknown_type
	 */
	private function _initActionGetContents(array $info)
	{
		G_Echo::l2("initializing action getContents\n");
		//if it is root action
		if ($info['actionId'] === $info['parentId']) {
			//then the input data is contained in data
			//if the constructor has a parameter then it is considered as the input data and the root action
			$this->_currentAction = new G_Miner_Engine_BluePrint_Action_GetContents($info['data']);
		} else {
			$this->_currentAction = new G_Miner_Engine_BluePrint_Action_GetContents();
		}
		$this->_currentAction->setAsOptional($info['isOpt']);
		//see if it uses callback
		$callbackInfo = G_Db_Registry::getInstance($this)->getActionCallbackMethodName($info['actionId']);
		if (false !== $callbackInfo) {
			if (null === $this->_callbackClassInstance) {
				throw new G_Miner_Engine_BluePrint_Exception('the current action extract wants to use method hook, but the bluePrint did not manage to instantiate the method class');
			}
			$row = current($callbackInfo);
			G_Echo::l1($row['methodName']);
			//if the callbacInstance is null it will throw an exception because of param type hint
			$cW = new G_Miner_Engine_BluePrint_Action_GetContents_Callback_Wrapper($this->_callbackClassInstance, $row['methodName']);
			
			//not all callbacks have a mapping (ex: a root get contents that uses itself as input)
			$callbackMapping = G_Db_Registry::getInstance($this)->getActionCallbackParamsToGroupMapping($info['actionId']);
			G_Echo::l2("retrieving action callback params to group mapping : \n" . print_r($callbackMapping, true));
			$callbackParamGroupMapping = array();//set blank mapping
			if (false !== $callbackMapping) {
				//reshape array
				foreach ($callbackMapping as $row) {
					$callbackParamGroupMapping[$row['paramNum']] = $row['regexGroup'];
				}
			} else {
				//default group mapping
				$callbackParamGroupMapping = array(1);
			}
			$cW->setParamToGroupMapping($callbackParamGroupMapping);
			$this->_currentAction->setCallbackWrapper($cW);
		}
	}
	
	/**
	 * 
	 * @param array $info
	 * @return void
	 */
	private function _initActionExtract(array $info)
	{
		G_Echo::l2("initializing action extract\n");
		//create the instance
		$this->_currentAction = new G_Miner_Engine_BluePrint_Action_Extract($info['data'], (1 === (integer) $info['useMatchAll']));
		G_Echo::l2("initActionExtract Id : {$info['actionId']}, regexData : " . print_r($info['data'], true));
		//query Db to get the group mapping (final results mapping)
		$groupMapping = G_Db_Registry::getInstance($this)->getActionGroupToEntityMapping($info['actionId']);
		//ensure there are rows
		if (false !== $groupMapping) {
			//set the group mapping even if empty
			$this->_currentAction->setGroupMapping($groupMapping);
		}
		$this->_currentAction->setAsOptional($info['isOpt']);
		$interceptMap = G_Db_Registry::getInstance($this)->getActionGroupToMethodNameAndInterceptType($info['actionId']);
		if (false !== $interceptMap) {
			G_Echo::l2("uses intercept\n" . print_r($interceptMap, true));
			if (null === $this->_methodClassInstance) {
				throw new G_Miner_Engine_BluePrint_Exception('the current action extract wants to use method hook, but the bluePrint did not manage to instantiate the method class');
			}
			$mW = new G_Miner_Engine_BluePrint_Action_Extract_Method_Wrapper($this->_methodClassInstance, $interceptMap);
			G_Echo::l1($this->_methodClassInstance);
			$this->_currentAction->setMethodWrapper($mW);
		}
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getCallbackInstance()
	{
		if (null === $this->_callbackClassInstance) {
			throw new G_Miner_Engine_BluePrint_Exception('There is no callback instance set in this blue print');
		}
		return $this->_callbackClassInstance;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getMethodInstance()
	{
		if (null === $this->_methodClassInstance) {
			throw new G_Miner_Engine_BluePrint_Exception('There is no callback instance set in this blue print');
		}
		return $this->_methodClassInstance;
	}
	
	/**
	 * Chains the action to the right parent
	 * and sets from which parent group the 
	 * action must take its input data (if it
	 * is an instance of extract)
	 * 
	 * @param $action
	 * @param $currentParentId
	 * @param $inputDataGroup
	 * @return unknown_type
	 */
	private function _chain($currentParentId, $inputDataGroup)
	{
		//first time entering chain should be root
		if ($this->_currentAction->getId() === $currentParentId) {
			$this->_currentAction->setAsRoot();
		} else {
			//find the right parent and add child
			$this->_findParent($currentParentId)->addChild($this->_currentAction);
		}
		//Point the chain to the last action
		$this->_lastAction = $this->_currentAction;
		//also set the group for input
		if ($this->_lastAction->getParent() instanceof G_Miner_Engine_BluePrint_Action_Extract) {
			$this->_lastAction->setInputDataGroup($inputDataGroup);
		}
	}
	
	/**
	 * 
	 * @param unknown_type $parentId
	 * @return unknown_type
	 */
	private function _findParent($parentId)
	{
		//get the youngest action
		$action = $this->_lastAction;
		while ($action->getId() !== $parentId) {
			if ($action->isRoot()) {
				throw new G_Miner_Engine_BluePrint_Exception("Did not find parent in stack while rolling back");
			}
			$action = $action->getParent();
		}
		return $action;
	}
	
	/**
	 * Proxy
	 * 
	 * @return unknown_type
	 */
	public function getRoot()
	{
		if ($this->_lastAction === null || 
		  !($this->_lastAction instanceof G_Miner_Engine_BluePrint_Action_Abstract)) {
		  	throw new G_Miner_Engine_BluePrint_Exception('There are no actions in blueprint $this->_lastAction : ' . print_r($this->_lastAction, true));
		}
		return $this->_lastAction->getRoot();
	}
	
	/**
	 * Returns the action with the id
	 * 
	 * @return unknown_type
	 */
	public function getAction($id)
	{
		$id = (integer) $id;
		if (!isset($this->_actionStack[$id])) {
			throw new G_Miner_Engine_BluePrint_Exception("There is no action with id : $id in bluePrint");
		}
		//G_Echo::l1($this->_actionStack[$id]);
		return $this->_actionStack[$id];
	}
	
	/**
	 * 
	 * @param unknown_type $actionId
	 * @param unknown_type $input
	 * @return unknown_type
	 */
	static public function updateActionInputData($actionId, $input)
	{
		G_Db_Registry::getInstance('G_Miner_Engine_BluePrint_Action_Savable')->updateActionInputData($actionId, $input);
	}
}