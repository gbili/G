<?php
/**
 * All classes wanting to get an id must extend this 
 * 
 * @author gui
 *
 */
class G_Molecule_Savable
extends G_Molecule_Abstract
implements G_Db_Buttons_Interface
{
	/**
	 * There are two save loops for the elements
	 * set in the instance :
	 *  - PRE_SAVE_LOOP :
	 * 		saves its elements before 
	 * 		saving this instance
	 *  - POST_SAVE_LOOP:
	 *  	save its elements after
	 *  	saving this instance
	 * This is useful when you have dependencies
	 * between elements in Db.
	 * 
	 * @var unknown_type
	 */
	const PRE_SAVE_LOOP = 0;
	const POST_SAVE_LOOP = 1;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_id;
	
	/**
	 * Allow subclasses to change the requestor class name
	 * It will be passed to G_Db_Registry
	 * 
	 * @var unknown_type
	 */
	private $_requestorClassName = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_keysOfElementsToSave = array();
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_keysOfElementsInstanceOfSelf = array();
	
	/**
	 * This avoid the infinite loop
	 * when there is a two way reference
	 * 
	 * @var unknown_type
	 */
	private $_hasSaveAllreadyBeenCalled = false;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_passTableNameToRequestor = false;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_customRequestorTableName = null;

	/**
	 * 
	 * @var unknown_type
	 */
	private $_differentRequestorPrefixedAdapter = null;
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		parent::__construct();
		//these will hold the elements that will be saved
		$this->_keysOfElementsToSave[self::PRE_SAVE_LOOP] = array();
		$this->_keysOfElementsToSave[self::POST_SAVE_LOOP] = array();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function isPassTableNameToRequestor()
	{
		return $this->_passTableNameToRequestor;
	}
	
	/**
	 * This allows the sub class to tell
	 * this class to set the requestors
	 * table name from a custom table name
	 * or if not available by guessing it
	 * 
	 * @return unknown_type
	 */
	public function setPassTableNameToRequestor()
	{
		$this->_passTableNameToRequestor = true;
	}
	
	/**
	 * When this custom table name is set
	 * then this class will not try to guess
	 * it from the sub class name
	 * 
	 * @param unknown_type $tableName
	 * @return unknown_type
	 */
	public function setCustomRequestorTableName($tableName)
	{
		if (false === $this->isPassTableNameToRequestor()) {
			$this->setPassTableNameToRequestor();
		}
		$this->_customRequestorTableName = (string) $tableName;
	}
	
	/**
	 * This will try to create a table name
	 * that is the same as the class name,
	 * but without the _Savable part
	 * 
	 * @return unknown_type
	 */
	private function _getTableNameGuess()
	{
		$tName = get_class($this);
		$c = mb_strlen('_Savable');
		if ('_Savable' === mb_substr($tName, -$c)) {
			$tName = mb_substr($tName, 0, -$c);
		}
		return $tName;
	}
	
	/**
	 * return the id and save if
	 * necessary, to retrieve it
	 * 
	 * @return unknown_type
	 */
	final public function getId()
	{
		if (null === $this->_id) {
			$this->save();
		}
		return $this->_id;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	final public function hasId()
	{
		return (null !== $this->_id);
	}
	
	/**
	 * This can only be called if the Db
	 * input lock is opened
	 * 
	 * @param unknown_type $id
	 * @return unknown_type
	 */
	final public function setId($id)
	{
		$this->_id = $id;
		return $this;
	}
	
	/**
	 * Allows to tell the requestor
	 * that it has to talk to the Db with
	 * a different prefixed adapter than the one
	 * specified by the subclass requestor's class
	 * name (which is used as prefix)
	 * 
	 * @param unknown_type $prefix
	 * @return unknown_type
	 */
	public function setDifferentRequestorPrefixedAdapter($prefix)
	{
		$this->_differentRequestorPrefixedAdapter = $prefix;;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getDifferentRequestorPrefixedAdapter()
	{
		if (null === $this->_differentRequestorPrefixedAdapter) {
			throw new G_Molecule_Savable_Exception('the different requestor prefixed adapter is not set');
		}
		return $this->_differentRequestorPrefixedAdapter;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function useDifferentRequestorPrefixedAdapter()
	{
		return null !== $this->_differentRequestorPrefixedAdapter;
	}
	
	/**
	 * Allow subclass to change the requestor class name
	 * 
	 * @param unknown_type $className
	 * @return unknown_type
	 */
	public function setRequestorClassName($className)
	{
		$this->_requestorClassName = (string) $className;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getRequestorClassName()
	{
		return $this->_requestorClassName;
	}
	
	/**
	 * The behaviour of _setElement is change to output
	 * all elements when callin to array
	 * 
	 * (non-PHPdoc)
	 * @see Molecule/G_Molecule_Abstract#_setElement($key, $value, $keyInToArrayReturnArray)
	 */
	protected function _setElement($key, $value, $saveLoop = self::PRE_SAVE_LOOP)
	{
		parent::_setElement($key, $value, true);//put all keys in return array
		$this->_manageSaveLoop($key, $value, $saveLoop);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Molecule/G_Molecule_Abstract#_useKeyAsArrayAndPushValue($key, $value, $keyInToArrayReturnArray)
	 */
	protected function _useKeyAsArrayAndPushValue($key, $value, $saveLoop = self::PRE_SAVE_LOOP)
	{
		parent::_useKeyAsArrayAndPushValue($key, $value, true);
		$this->_manageSaveLoop($key, $value, $saveLoop);
	}
	
	/**
	 * 
	 * @param unknown_type $key
	 * @param unknown_type $value
	 * @param unknown_type $saveLoop
	 * @return unknown_type
	 */
	private function _manageSaveLoop($key, $value, $saveLoop)
	{
		//if it is instance of self or an array of instances of self
		// add its key to pre|post save array so it will be saved
		if (($value instanceof self 
				|| (is_array($value) 
					&& array_keys($value) === range(0, count($value) - 1)
					&& current($value) instanceof self)) //only save arrays that have elements of savable
			&& !in_array($key, $this->_keysOfElementsToSave[$saveLoop])) {
			//this will avoid the instance passed in $value to be saved before this one
			//this way, the id of this instance will be available to $value instance
			$this->_keysOfElementsToSave[$saveLoop][] = $key;
		}
	}
	
	/**
	 * This will store the molecule in the Db
	 * and it will populate the id
	 * 
	 * @return unknown_type
	 */
	public function save()
	{
		G_Echo::l3('entering molecule::save() of '. get_class($this) . "\n");
		if (true === $this->_hasSaveAllreadyBeenCalled) {
			G_Echo::l3('exiting already called molecule::save() of '. get_class($this) . "\n");
			return;
		}
		//this will avoid the infinite loop in case there
		//are two instances referencing one an other and 
		//they are both in their respective PRE_SAVE_LOOP
		//when this case arises it is the result of bad
		//programming so it will throw an exception in
		//_saveIfNeeded();
		$this->_hasSaveAllreadyBeenCalled = true;
		
		G_Echo::l3('>----opening------>PRE SAVE LOOP >----------> of '. get_class($this) . "\n");
		/*
		 * ----------------- PRE SAVE > save all instances that this one depends on to save itself
		 */
		if (!empty($this->_keysOfElementsToSave[self::PRE_SAVE_LOOP])) {
			$this->_saveLoop(self::PRE_SAVE_LOOP);
		}
		/*
		 * ----------------- SAVE > save the current instance
		 */
		G_Echo::l3('<---closing--<PRE SAVE LOOP <---------------< of '. get_class($this) . "\n");
		//allow subclass to change the requestor class name
		$registryParam = (null !== $this->_requestorClassName)? $this->_requestorClassName : $this;
		
		//once the tree has been saved, this instance 
		//now Db can save and set the id, because it has all it needs
		G_Echo::l3('calling requestor::save() of '. get_class($this) . "\n");
		$reqInst = G_Db_Registry::getInstance($registryParam);
		
		//allow to change the requestor Db adapter
		if ($this->useDifferentRequestorPrefixedAdapter()) {
			$reqInst->setDifferentPrefixedAdapter($this->getDifferentRequestorPrefixedAdapter());
		}
		
		//allow to tell the requestor what table name to work on
		if ($this->isPassTableNameToRequestor()) {
			$reqInst->setTableName((string) (null === $this->_customRequestorTableName)? $this->_getTableNameGuess() : $this->_customRequestorTableName);
		}
		$reqInst->save($this);

		//Db must set the id of this class once saved
		if (null === $this->_id) {
			throw new G_Molecule_Savable_Exception('Your Db talker must set the G_Molecule_Savable instance\'s id.');
		}
		G_Echo::l3('>----opening------>POST SAVE LOOP >----------> of '. get_class($this) . "\n");
		/*
		 * ----------------- POST SAVE > save instances that on this ones id to save themseleves
		 */
		if (!empty($this->_keysOfElementsToSave[self::POST_SAVE_LOOP])) {
			$this->_saveLoop(self::POST_SAVE_LOOP);
		}
		G_Echo::l3('<---closing--<POST SAVE LOOP <---------------< of '. get_class($this) . "\n");
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasSaveAllreadyBeenCalled()
	{
		return $this->_hasSaveAllreadyBeenCalled;
	}
	
	/**
	 * 
	 * @param unknown_type $type
	 * @return unknown_type
	 */
	private function _saveLoop($type)
	{
		foreach ($this->_keysOfElementsToSave[$type] as $key) {
			$e = $this->getElement($key);
			if (!is_array($e)) {
				$this->_saveIfNeeded($key, $e, $type);
			} else {
				foreach ($e as $elm) {
					$this->_saveIfNeeded($key, $elm, $type);
				}
			}
		}
	}
	
	/**
	 * Determines whether to save or not it avoids duplicate savings
	 * 
	 * @param G_Molecule_Savable $e
	 * @return unknown_type
	 */
	private function _saveIfNeeded($k, G_Molecule_Savable $e, $callingLoopType = self::PRE_SAVE_LOOP)
	{	
		if ($e->hasId()) {//if it has not already been successfully saved) {
			return;
		}
		
		if ($callingLoopType === self::PRE_SAVE_LOOP
		 && !in_array($k, $this->_keysOfElementsToSave[self::POST_SAVE_LOOP])) {
			if ($e->hasSaveAllreadyBeenCalled()) {
				throw new G_Molecule_Savable_Exception('You have a two way reference and both elements are in one\'s other PRE_SAVE_LOOP, which results in an infinite loop (hadn\'t this exception been thrown), you can solve this by setting one of those references in the POST_SAVE_LOOP, by calling _setElement(key, value, G_Molecule_Savable::POST_SAVE_LOOP) : ' . $k . ', ' . print_r($e,true));
			}
			//if the element does not require $this to be saved before itself
			G_Echo::l3("PRE LOOP : saving $k from " . get_class($this) . "\n");
			$e->save();
		} else if ($callingLoopType === self::POST_SAVE_LOOP) {
			G_Echo::l3("POST LOOP : saving $k from " . get_class($this) . "\n");
			$e->save();
		}
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function delete()
	{
		//allow subclass to change the requestor class name
		$registryParam = (null !== $this->_requestorClassName)? $this->_requestorClassName : $this;
		G_Db_Registry::getInstance($registryParam)->delete($this);
	}
}