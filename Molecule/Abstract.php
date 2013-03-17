<?php
/**
 * This class is used instead of reflection
 * because it avoids lots of code repetition like
 * if !isset($this->_property) throw ...
 * 
 * @author gui
 *
 */
abstract class G_Molecule_Abstract
{
	const SOURCE_USEKEYASARRAYANDPUSHVALUE = 21;
	const SOURCE_SETELEMENT = 22;
	
	/**
	 * Contains all elements as an array of atoms
	 * wich are sort of wrappers that ease scope
	 * resolution
	 * 
	 * @var array
	 */
	private $_elements = array();
	
	/**
	 * This is the set of keys in $_elements that will be
	 * considered by the method toArray() if a key in
	 * $_elements is not in $_keysToArray then it will not
	 * be part of the array returned by toArray()
	 * when turning 
	 * @var unknown_type
	 */
	private $_keysToArray = array();
	
	/**
	 * Avoid memory exhaustion due to recursive to array calls
	 *  
	 * @var unknown_type
	 */
	static private $_instancesWhereToArrayMethodWasCalled = array();
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_keysInElementsUsedAsArray = array();
	
	/**
	 * Lock the object to forbid Db input
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
	}
	
	/**
	 * 
	 * @param unknown_type $key
	 * @return unknown_type
	 */
	public function isSetKey($key)
	{
		return isset($this->_elements[$key]);
	}
	
	/**
	 * 
	 * @param $key
	 * @return unknown_type
	 */
	public function getElement($key)
	{
		if (!isset($this->_elements[$key])) {
			throw new G_Molecule_Exception('The element with key : ' . $key . ', is not set.');
		}
		return $this->_elements[$key];
	}
	
	/**
	 * 
	 * @param $key
	 * @return unknown_type
	 */
	public function getElements()
	{
		return $this->_elements;
	}
	
	/**
	 * 
	 * @param $key
	 * @param $value
	 * @param $keyInToArrayReturnArray
	 * @return unknown_type
	 */
	protected function _setElement($key, $value, $keyInToArrayReturnArray = true)
	{	
		//if the key was already set, and it is exactly the same throw up 
		if (isset($this->_elements[$key]) && $this->_elements[$key] === $value) {
			throw new G_Molecule_Exception("Element with key: '$key', is already set. with exactly the same value, code differently the value was and is : " . print_r($this->_elements[$key], true));
		}

		$this->_elements[$key] = $value;
		if (true === $keyInToArrayReturnArray) {
			$this->_keysToArray[$key] = true;
		}
	}
	
	/**
	 * 
	 * @param unknown_type $key
	 * @return unknown_type
	 */
	protected function _unsetElement($key)
	{
		if (isset($this->_elements[$key])) {
			unset($this->_elements[$key]);
		}
		if (isset($this->_keysToArray[$key])) {
			unset($this->_keysToArray[$key]);
		}
		if (isset($this->_keysInElementsUsedAsArray[$key])) {
			unset($this->_keysInElementsUsedAsArray[$key]);
		}
	}
	
	/**
	 * Create a G_Molecule_Atom_Stack instance,
	 * under the key $key, and then push the value
	 * $value into the stack. If the value is not
	 * set from the same origin than the first value
	 * of the stack, the G_Molecule_Atom_Stack::pushValue()
	 * method will throw an exception.
	 * However if $ifIsArrayMerge = true, this method will call
	 * G_Molecule_Atom_Stack::mergeArray() instead which 
	 * will not necessarily throw up, it will depend on the
	 * value of $overwriteOrigin
	 * @see G_Molecule_Atom_Stack::mergeArray() 
	 * for complete behaviour explanation
	 * @see //IMPOTANT NOTE IN BODY
	 * @param unknown_type $key
	 * @param unknown_type $value
	 * @return unknown_type
	 */
	protected function _useKeyAsArrayAndPushValue($key, $value, $keyInToArrayReturnArray = true)
	{
		if (!isset($this->_elements[$key])) {
			$this->_elements[$key] = array();
			$this->_keysInElementsUsedAsArray[$key] = true;
			if (true === $keyInToArrayReturnArray) {
				$this->_keysToArray[$key] = true;
			}
		}
		if (!is_array($this->_elements[$key])) {
			throw new G_Molecule_Exception('You are trying to use a key as an array and it was previously not intended for that');
		}
		//cast values to same type when object
		if (!empty($this->_elements[$key])) {
			$refTypeElement = current($this->_elements[$key]);
			if (is_object($refTypeElement) && !($value instanceof $refTypeElement)) {
				throw new G_Molecule_Stack_Exception('The passed value is not the same type as the last one you passed');
			}
		}
		$this->_elements[$key][] = $value;
	}
	
	/**
	 * recursively create an array
	 * 
	 * @return unknown_type
	 */
	public function toArray()
	{	
		$finalArray = array();
		//only add the keys that are present in $this->_keysToArray
		$elementsToArray = array_intersect_key($this->_elements, $this->_keysToArray);
		foreach ($elementsToArray as $key => $value) {
			if ($value instanceof self && $this->_callToArrayOnInstance($value)) {
				$value = $value->toArray(); //returns an array
			} else if (isset($this->_keysInElementsUsedAsArray[$key])
			 && (current($value) instanceof self)) {
				$array = array();
				foreach ($value as $v) {
					if ($v instanceof self && $this->_callToArrayOnInstance($v)) {
						$array[] = $v->toArray();
					}
				}
				$value = $array;
			}
			$finalArray[$key] = $value;
		}
		return $finalArray;
	}
	
	/**
	 * Adds the instance to instances arrayed
	 * and returns false or true to let the caller
	 * know if it should call toArray() or not
	 * 
	 * @param $instance
	 * @return unknown_type
	 */
	private function _callToArrayOnInstance($instance)
	{
		if (false === array_search($this, self::$_instancesWhereToArrayMethodWasCalled, true)) {
			self::$_instancesWhereToArrayMethodWasCalled[] = $instance;
			return true;
		}
		return false;
	}
}