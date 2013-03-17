<?php
/**
 * This will contain an object representation of
 * the action table set.
 * The actions will inject themselves the input data
 * from their parents
 * They will also know what part of the result means
 * what, and will make the final results (if there is any)
 * be available from the public method spit().
 * 
 * 
 * @author gui
 *
 */
abstract class G_Miner_Engine_BluePrint_Action_Abstract
{

	const MORE_CHILDREN_TO_EXECUTE = 21;
	const MORE_RESULTS_TO_EXECUTE = 22;
	const CANNOT_EXECUTE_AWAITING_INPUT = 23;
	const NO_INPUT_IS_OPT_NO_EXEC = 41;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_id = null;
	
	/**
	 * 
	 * @var G_Miner_Engine_BluePrint
	 */
	private $_bluePrint = null;
	
	/**
	 * Lets the G_Miner_Engine_Engine know whether
	 * it has to create a new instance of
	 * G_Miner_Engine::$_instancesClassName
	 * 
	 * @todo integrte it
	 * @var unknown_type
	 */
	private $_isNewInstanceGeneratingPoint = null;
	
	/**
	 * Keep a pointer of the root action
	 * so it can be accessed quicker
	 * than by doing a loop and calling 
	 * getParent on all the actions chain...
	 * 
	 * @var G_Miner_Engine_BluePrint_Action_Abstract
	 */
	private $_rootAction = null;
	
	/**
	 * Points to the parent action which
	 * may be itself in case it is the root
	 * 
	 * @var G_Miner_Engine_BluePrint_Action_Abstract
	 */
	private $_parentAction = null;
	
	/**
	 * If false means it is the leaf action
	 * 
	 * @var G_Miner_Engine_BluePrint_Action_Abstract
	 */
	private $_childrenActionStack = array();
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_hasChildAction = null;
	
	/**
	 * When go to next child action
	 * reaches the end of _childrenActionStack()
	 * this is true;
	 * 
	 * @var unknown_type
	 */
	private $_needRewindChildrenStack = false;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_currentChild = null;
	
	/**
	 * The parent result group from which the input is taken from
	 * 
	 * @var integer
	 */
	private $_takeInputFromGroupNumber = null;
	
	/**
	 * The group in the parent results from which
	 * this object will get its input data when
	 * callin getParent()->getResult(_groupForInputData)
	 * 
	 * @var integer
	 */
	protected $_groupForInputData = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_isOpt = false;
	
	/**
	 * Gives humans some insight about the action
	 * 
	 * @var unknown_type
	 */
	private $_title = null;
	
	/**
	 * Whenever execute() is called this should
	 * be set to true and clear() should set it
	 * to false
	 * 
	 * @var boolean
	 */
	protected $_isExecuted = false;
	
	/**
	 * This will be saved by the engine
	 * in case it is the new instance
	 * generating point to let him know
	 * where to start the dumping process
	 * next time
	 * 
	 * It does never get cleared, so
	 * if the parent action gives some
	 * input it should allways be set
	 * 
	 * 
	 * @var unknown_type
	 */
	protected $_lastInput = null;
	
	/**
	 * This allows the action to store messages
	 * that can be retrieved by te engine
	 * in order to change flow or behaviour
	 * @var unknown_type
	 */
	protected $_message = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_otherActionInput = null;
	
	/**
	 * Contains the action id from
	 * which this action will take
	 * input once it is available
	 * 
	 * @var unknown_type
	 */
	protected $_otherInputActionId = null;
	
	/**
	 * Group number for input from other action
	 * 
	 * @var unknown_type
	 */
	protected $_otherActionGroupForInputData = null;
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		
	}
	
	/**
	 * 
	 * @param unknown_type $id
	 * @return unknown_type
	 */
	public function setId($id)
	{
		$this->_id = (integer) $id;
	}
	
	/**
	 * Give some human insight about the action
	 * 
	 * @param unknown_type $title
	 * @return unknown_type
	 */
	public function setTitle($title)
	{
		$this->_title = (string) $title;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasTitle()
	{
		return '' !== $this->_title;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getTitle()
	{
		return $this->_title;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getId()
	{
		if (null === $this->_id) {
			throw new G_Miner_Engine_BluePrint_Action_Exception('there is no id set for this action');
		}
		return $this->_id;
	}
	
	/**
	 * 
	 * @param unknown_type $input
	 * @return unknown_type
	 */
	public function injectInput($input)
	{
		$this->_otherActionInput = $input;
	}
	
	/**
	 * Returns the action that should 
	 * be used as input when available
	 * 
	 * @return unknown_type
	 */
	public function getOtherInputActionId()
	{
		if (null === $this->_otherInputActionId) {
			throw new G_Miner_Engine_BluePrint_Action_Exception("There is no other input actionId");
		}
		return $this->_otherInputActionId;
	}
	
	/**
	 * Returns the action that should 
	 * be used as input when available
	 * 
	 * @return unknown_type
	 */
	public function setOtherInputActionInfo($id, $groupForInputData = null)
	{
		$this->_otherInputActionId = (integer) $id;
		$this->_otherActionGroupForInputData = $groupForInputData;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getBluePrint()
	{
		if (null === $this->_bluePrint) {
			throw new G_Miner_Engine_BluePrint_Action_Exception('Blue print was not set');
		}
		return $this->_bluePrint;
	}
	
	/**
	 * 
	 * @param G_Miner_Engine_BluePrint $b
	 * @return unknown_type
	 */
	public function setBluePrint(G_Miner_Engine_BluePrint $b)
	{
		$this->_bluePrint = $b;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getLastInput()
	{
		return $this->_lastInput;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasLastInput()
	{
		return null !== $this->_lastInput;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasMessage()
	{
		return $this->_message !== null;
	}

	/**
	 * 
	 * @return unknown_type
	 */
	public function getMessage()
	{
		return $this->_message;
	}

	/**
	 * 
	 * @return unknown_type
	 */
	public function isRoot()
	{
		if (null === $this->_rootAction) {
			throw new G_Miner_Engine_BluePrint_Exception('There is not root action set for this action so you cannot call isRoot()');
		}
		return ($this === $this->_rootAction);
	}
	
	/**
	 * This function should only be called from within the setChildAction function
	 * 
	 * @param G_Miner_Engine_BluePrint_Action_Abstract$action
	 * @return unknown_type
	 */
	public function setParent(G_Miner_Engine_BluePrint_Action_Abstract $action, $setter)
	{
		if (!($setter instanceof G_Miner_Engine_BluePrint_Action_Abstract)) {
			throw new G_Miner_Engine_BluePrint_Action_Exception('This function should only be called from G_Miner_Engine_BluePrint_Action_Abstract and subclasses');
		}
		$this->_parentAction = $action;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getParent()
	{
		if (null === $this->_parentAction) {
			throw new G_Miner_Engine_BluePrint_Action_Exception('there is no parent action set right now, call setParent() before calling getParent()');
		}
		return $this->_parentAction;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function setAsRoot()
	{
		if ($this->_rootAction !== null) {
			throw new G_Miner_Engine_BluePrint_Action_Exception('The root  has already been set');
		}
		$this->_rootAction = $this;
		$this->setParent($this, $this);
	}
	
	/**
	 * 
	 * @param G_Miner_Engine_BluePrint_Action_Abstract$action
	 * @return unknown_type
	 */
	public function setRoot(G_Miner_Engine_BluePrint_Action_Abstract $action, $setter)
	{
		if (!($setter instanceof G_Miner_Engine_BluePrint_Action_Abstract)) {
			throw new G_Miner_Engine_BluePrint_Action_Exception('Lock is wrong, setRoot() should only be called from within setChild() function');
		}
		$this->_rootAction = $action;
	}
	
	/**
	 * Returns the pointer to the root action
	 * 
	 * @return unknown_type
	 */
	public function getRoot()
	{
		//if there is no root action, it is the root action
		if (null === $this->_rootAction) {
			throw new G_Miner_Engine_BluePrint_Action_Exception('The root action is not set');
		}
		return $this->_rootAction;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function isExecuted()
	{
		return $this->_isExecuted;
	}
	
	/**
	 * When there is at least one child
	 * and clear() has has not outdated
	 * it yet, the child can be returned
	 * 
	 * @return unknown_type
	 */
	public function isReadyToReturnChild()
	{
		return ($this->hasChildren() && null !== $this->_currentChild && !$this->needRewindChildrenStack());
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getChild()
	{
		if (!$this->isReadyToReturnChild()) {
			throw new G_Miner_Engine_BluePrint_Action_Exception("There is no child, or the children stack arrived to the end, call clear() to renew stack");
		}
		return $this->_currentChild;
	}
	
	/**
	 * has the action has any children?
	 * 
	 * @return unknown_type
	 */
	public function hasChildren()
	{
		return !empty($this->_childrenActionStack);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getChildren()
	{
		return $this->_childrenActionStack;
	}
	
	/**
	 * Populates _currentChild
	 * If there childrenActionStack array
	 * pointer exceeds the last element
	 * it will return false
	 * It will also return false if there are
	 * no children
	 * 
	 * @return unknown_type
	 */
	public function _goToNextChildInStack()
	{
		if ($this->hasChildren()) {
			list($k, $this->_currentChild) = each($this->_childrenActionStack);
			return !($this->_needRewindChildrenStack = (null === $this->_currentChild));
		}
		return false;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function needRewindChildrenStack()
	{
		return $this->_needRewindChildrenStack;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function _rewindChildrenStack($skipCheck = false)
	{
		if ($this->hasChildren()) {
			if (false === $skipCheck && !$this->needRewindChildrenStack()) {
				throw new G_Miner_Engine_BluePrint_Action_Exception("The current children stack needs not to be rewinded untill it reaches end");
			}
			reset($this->_childrenActionStack);
			$this->_needRewindChildrenStack = false;
			//already place the first child in current child
			$this->_goToNextChildInStack();
		}
	}
	
	/**
	 * Allow multiple child actions per action
	 * There may be many child actions per parent
	 * so $this->_childrenActionStack can be an array
	 * of G_Miner_Engine_BluePrint_Action_Abstract or just
	 * a G_Miner_Engine_BluePrint_Action_Abstract
	 * 
	 * @return unknown_type
	 */
	public function setChild(G_Miner_Engine_BluePrint_Action_Abstract $action)
	{
		//ad child to stack
		$this->_childrenActionStack[] = $action;
		
		if (count($this->_childrenActionStack) === 1) {
			$this->_goToNextChildInStack();
		}

		//also set the parent and root of the $action in parameter
		$action->setParent($this, $this);
		$action->setRoot($this->getRoot(), $this);
	}
	
	/**
	 * 
	 * @param G_Miner_Engine_BluePrint_Action_Abstract $action
	 * @return unknown_type
	 */
	public function addChild(G_Miner_Engine_BluePrint_Action_Abstract $action)
	{
		$this->setChild($action);
	}
	
	/**
	 * The group from parent results where this action
	 * will take its input data
	 * 
	 * @param $groupNumber
	 * @return unknown_type
	 */
	public function setInputDataGroup($groupNumber)
	{
		if (!($this->getParent() instanceof G_Miner_Engine_BluePrint_Action_Extract)) {
			throw new G_Miner_Engine_BluePrint_Action_Extract_Exception('The parent action must be of type G_Miner_Engine_BluePrint_Action_Extract in order to set the group for input data');
		}
		$this->_groupForInputData = (integer) $groupNumber;
	}
	
	/**
	 * Tell whether engine should create new video entity
	 * instance from this action
	 * @TODO the new instance generatig point has a flaw when it is attached to an Extract action with matchAll
	 * we have to hook someplace the new instance generation so that there is an instance for every match in matchall
	 * 1. find at which point matchAll can give a hint on the numer of results.
	 * 2. once we have the number, add some sort of communication between the Extract action, and the Engine::manageNIGP()
	 * 3. make sure that all those instances are saved gracefully. 
	 * 
	 * @return boolean
	 */
	public function isNewInstanceGeneratingPoint()
	{
		return $this->_isNewInstanceGeneratingPoint;
	}
	
	/**
	 * Set the video entity starting point to true
	 * @return unknown_type
	 */
	public function setAsNewInstanceGeneratingPoint()
	{
		$this->_isNewInstanceGeneratingPoint = true;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function setAsOptional($bool)
	{
		$this->_isOpt = (boolean) $bool;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function isOptional()
	{
		return $this->_isOpt;
	}
	
	/**
	 * Returns the result of the action
	 * 
	 * @param $groupNumber if the result is divided into groups, this specifies which group to return
	 * @return unknown_type
	 */
	abstract public function getResult($groupNumber = null);
	
	/**
	 * Tells the engine whether to call spitt() or not
	 * 
	 * @return unknown_type
	 */
	abstract public function hasFinalResults();

	/**
	 * Once executed, if the action has final results it will
	 * return them as an assciative array
	 * ex : array(VideoName=>'the big lebowsky')
	 * otherwise it will return false
	 * @return unknown_type
	 */
	abstract public function spit();
	
	/**
	 * Will make the results available to the instance
	 * an will return true or false on success or fail
	 * 
	 * @return boolean
	 */
	abstract protected function _execute();
	
	/**
	 * 
	 * 
	 * @return unknown_type
	 */
	final public function execute()
	{
		G_Echo::l2("Abstract::execute({$this->getId()})\n");
		if (true === $this->_isOpt &&
			$this->getParent() instanceof G_Miner_Engine_BluePrint_Action_Extract &&
			null !== $this->_groupForInputData &&
			false === ($res = $this->getParent()->hasGroupNumber($this->_groupForInputData))) {
			G_Echo::l2("Abstract : execute() : \$this->getParent()->hasGroupNumber({$this->_groupForInputData}) = var_dump:\n");
			var_dump($res);
			return self::NO_INPUT_IS_OPT_NO_EXEC;
		}
		
		//call sublcass function
		$res = $this->_execute();
		
		//allow exceptions
		if (false === $this->_throwOnFalse()) {
			throw new G_Miner_Engine_BluePrint_Action_Exception("function G_Miner_Engine_BluePrint_Action_Abstract/Subclass::_thorwOnFalse() returned false.");
		}
		
		return $res;
	}
	
	/**
	 * This allows subclasses and abstract class
	 * to make each other execution crash in case some
	 * condition defined in here is not met.
	 * 
	 * To extend this function you should return
	 * the parent result coupled with your check
	 * result and an && operator
	 * 
	 * @return unknown_type
	 */
	protected function _throwOnFalse()
	{
		//make sure lastInput was set by subclass
		//after execution
		return (null !== $this->_lastInput);
	}
	
	/**
	 * Clear will empty $_results so new info is considered
	 * However if the $_results is an array from where different
	 * video entities have to take information, it will only shift
	 * the first result
	 * 
	 * @note make sure to call clear from the last child
	 * 
	 * @return bolean if true the downwards process can continue otherwise call clear() on parent action
	 * i.e. if true, there are more results and the child action should be executed again
	 */
	public function clear()
	{
		G_Echo::l2("Abstract::clear({$this->getId()})\n");
		if (false === $this->_isExecuted) {
			throw new G_Miner_Engine_BluePrint_Action_Extract_Exception('You cannot clear the instance it has not been executed already call execute().');
		}

		//some children need to execute on the same result
		G_Echo::l2("Abstract::clear() : has more children to execute? : ");
		if ($this->_goToNextChildInStack()) {
			G_Echo::l2("yes!\n"); 
			return self::MORE_CHILDREN_TO_EXECUTE;
		}
		G_Echo::l2("no! \n");
		//all children were executed for this result
		//renew the children if it is an array
		$this->_rewindChildrenStack();
		
		//execute subclass clear
		return $this->_clear();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	abstract protected function _clear();
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function fullClear()
	{
		G_Echo::l2("Abstract::fullClear({$this->getId()})\n");
		$this->_rewindChildrenStack(true);
		$this->_fullClear();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	abstract protected function _fullClear();
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function clearMeAndOffspring($thisFull = true)
	{
		G_Echo::l3("MyId : {$this->getId()}\n");
		if ($this->hasChildren()) {
			foreach ($this->getChildren() as $child) {
				G_Echo::l3("ParentId : {$this->getId()} => ");
				$child->clearMeAndOffspring();
			}
		}
		if (true === $thisFull) {
			$this->fullClear();
		} else {
			return $this->clear();
		}
	}
}