<?php
/**
 * This class is not meant for any great work, just to ensure
 * that the action gets all its data. And that it gets saved properly
 * 
 * extending classes must set the 'type' automatically on construction
 * 
 * 
 * @author gui
 *
 */
abstract class G_Miner_Engine_BluePrint_Action_Savable_Abstract
extends G_Molecule_Savable
{

	static private $_order = array();
	
	/**
	 * 
	 * @var unknown_type
	 */
	const DEFAULT_INPUT_PARENT_REGEX_GROUP_NUMBER = 1;

	/**
	 * Change requestor class name
	 * that will be used by G_Molecule_Savable
	 * when calling method save()
	 * @return unknown_type
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setRequestorClassName('G_Miner_Engine_BluePrint_Action_Savable');
	}
	
	/**
	 * This is the action title
	 * 
	 * @param unknown_type $title
	 * @return unknown_type
	 */
	public function setTitle($title)
	{
		$this->_setElement('title', (string) $title);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasTitle()
	{
		return $this->isSetKey('title');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getTitle()
	{
		return $this->getElement('title');
	}
	
	/**
	 * When calling this method, child should not have been added
	 * to parent
	 * 
	 * @param unknown_type $parent
	 * @param unknown_type $child
	 * @return unknown_type
	 */
	private function _manageOrder($parent, $child)
	{
		//see if parent has a rank
		if (false === ($parentRnk = self::getOrderRank($parent))) {
			if (!empty(self::$_order)) {
				throw new G_Miner_Engine_BluePrint_Action_Savable_Exception('The parent has no rank but some ranks have already been set, you must start adding childs from root cannot create a branch and then add it to trunk' . print_r(array_keys(self::$_order), true));
			}
			//this means the parent is root so it is setting itself
			self::$_order[] = $parent;
		}
		self::$_order[] = $child;
	}
	
	/**
	 * 
	 * @param unknown_type $element
	 * @return unknown_type
	 */
	static public function getOrderRank($element)
	{
		return array_search($element, self::$_order, true);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getRank()
	{
		if (false === $rnk = self::getOrderRank($this)) {
			throw new G_Miner_Engine_BluePrint_Action_Savable_Exception('the rank has not been set for this instance, it means that it was not chained to any parent (or child, when root)');
		}
		return $rnk;
	}
	
	/**
	 * This allows the action to be skipped and all its
	 * children when the parent action being extract did
	 * not generate any input for it
	 * 
	 * @return unknown_type
	 */
	public function setAsOptional()
	{
		$this->_setElement('isOpt', true);
	}

	/**
	 * 
	 * @return unknown_type
	 */
	public function getIsOptional()
	{
		if (!$this->isSetKey('isOpt')) {
			$this->_setElement('isOpt', false);
		}
		return $this->getElement('isOpt');
	}
	
	/**
	 * You can explicitely instantiate the right class or use this function
	 * to get the class of the type specified in param
	 * 
	 * @param integer $type
	 * @return unknown_type
	 */
	static public function getInstanceOfType($type)
	{
		switch ($type) {
			case G_Miner_Engine_BluePrint::ACTION_TYPE_EXTRACT;
				$instance = new G_Miner_Engine_BluePrint_Action_Extract_Savable();
				break;
			case G_Miner_Engine_BluePrint::ACTION_TYPE_GETCONTENTS;
				$instance = new G_Miner_Engine_BluePrint_Action_GetContents_Savable();
				break;
			default;
				throw new G_Miner_Engine_BluePrint_Action_Savable_Exception('The type you passed is not supported given : ' . print_r($type, true));
				break;
		}
		return $instance;
	}
	
	/**
	 * @TODO the new instance generatig point has a flaw when it is attached to an Extract action with matchAll
	 * we have to hook someplace the new instance generation so that there is an instance for every match in matchall
	 * 1. find at which point matchAll can give a hint on the numer of results.
	 * 2. once we have the number, add some sort of communication between the Extract action, and the Engine::manageNIGP()
	 * 3. make sure that all those instances are saved gracefully. 
	 * @return unknown_type
	 */
	public function setAsNewInstanceGeneratingPoint()
	{
		if (!$this->isSetKey('bluePrint')) {
			throw new G_Miner_Engine_BluePrint_Action_Savable_Exception('The bluePrint must me set with setBluePrint() before setting the action as newInstanceGeneratingPoint');
		}
		$this->getBluePrint()->setNewInstanceGeneratingPointAction($this);
		$this->_setElement('isNewInstanceGeneratingPoint', 1);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function isNewInstanceGeneratingPoint()
	{
		return $this->isSetKey('isNewInstanceGeneratingPoint');
	}
	
	/**
	 * Also sets the element bluePrintId
	 * 
	 * @param G_Miner_Engine_BluePrint_Savable $bP
	 * @return unknown_type
	 */
	public function setBluePrint(G_Miner_Engine_BluePrint_Savable $bP)
	{
		$this->_setElement('bluePrint', $bP);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasBluePrint()
	{
		return $this->isSetKey('bluePrint');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getBluePrint()
	{
		return $this->getElement('bluePrint');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function isRoot()
	{
		return ($this->getType() === G_Miner_Engine_BluePrint::ACTION_TYPE_GETCONTENTS && !$this->isSetKey('parentAction'));
	}
	
	/**
	 * 
	 * @param $parentId
	 * @return unknown_type
	 */
	public function setParent(G_Miner_Engine_BluePrint_Action_Savable_Abstract $action)
	{
		$this->_setElement('parentAction', $action);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasParent()
	{
		return $this->isSetKey('parentAction');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getParent()
	{
		return $this->getElement('parentAction');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function addChild(G_Miner_Engine_BluePrint_Action_Savable_Abstract $action)
	{
		$action->setBluePrint($this->getBluePrint());
		$action->setParent($this);
		//this will set the rank of the parent and child
		$this->_manageOrder($this, $action);
		$this->_useKeyAsArrayAndPushValue('childAction', $action, G_Molecule_Savable::POST_SAVE_LOOP);
	}
	
	/**
	 * 
	 * @param G_Miner_Engine_BluePrint_Action_Savable_Abstract $action
	 * @param unknown_type $inputGroup
	 * @return unknown_type
	 */
	public function injectResultTo(G_Miner_Engine_BluePrint_Action_Savable_Abstract $action, $inputGroup = null)
	{
		if (null !== $inputGroup) {
			if (!($this instanceof G_Miner_Engine_BluePrint_Action_Extract_Savable)) {
				throw new G_Miner_Engine_BluePrint_Action_Savable_Exception('You cannot set the input group if the injecting action is not of type extract');
			}
			$action->setInjectInputGroup($inputGroup);
		}
		$this->_setElement('injectedAction', $action);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function injectsAction()
	{
		return $this->isSetKey('injectedAction');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getInjectedAction()
	{
		return $this->getElement('injectedAction');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasInjectInputGroup()
	{
		return $this->isSetKey('injectInputGroup');
	}
	
	/**
	 * 
	 * @param unknown_type $group
	 * @return unknown_type
	 */
	public function setInjectInputGroup($group)
	{
		$this->_setElement('injectInputGroup', (integer) $group);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getInjectInputGroup()
	{
		return $this->getElement('injectInputGroup');
	}

	/**
	 * 
	 * @return array of G_Miner_Engine_BluePrint_Action_Savable_Abstract
	 */
	public function getChildren()
	{
		return $this->getElement('childAction');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasChildren()
	{
		return $this->isSetKey('childAction');
	}

	/**
	 * 
	 * @param $inputParentRegexGroupNumber
	 * @return unknown_type
	 */
	public function setInputParentRegexGroupNumber($inputParentRegexGroupNumber)
	{
		$this->_setElement('inputParentRegexGroupNumber', (integer) $inputParentRegexGroupNumber);
	}
	
	/**
	 * Sets the input group to default when the parent is of type extract
	 * otherwise it sets the input group to no group specified in requestor
	 * 
	 * 
	 * @return unknown_type
	 */
	public function getInputParentRegexGroupNumber()
	{
		if (!$this->isSetKey('inputParentRegexGroupNumber')) {
			if ($this->hasParent() && $this->getParent()->getType() === G_Miner_Engine_BluePrint::ACTION_TYPE_EXTRACT) {
				$this->setInputParentRegexGroupNumber(self::DEFAULT_INPUT_PARENT_REGEX_GROUP_NUMBER);
			} else {
				$this->setInputParentRegexGroupNumber(G_Miner_Engine_BluePrint_Action_Savable_Db_Req::DEFAULT_NO_INPUT_PARENT_REGEX_GROUP_NUMBER);
			}
		}
		return $this->getElement('inputParentRegexGroupNumber');
	}
	
	/**
	 * 
	 * @param unknown_type $data
	 * @return unknown_type
	 */
	public function setData($data)
	{
		$this->_setElement('data', (string) $data);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getData()
	{
		return $this->getElement('data');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasData()
	{
		return $this->isSetKey('data');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getType()
	{
		return $this->getElement('type');
	}
}