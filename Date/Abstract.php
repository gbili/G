<?php
/**
 * This class is meant to get a string as input and return
 * a sanitized date
 * It uses G_Date_Regex to retrieve the date parts
 * from the string
 * 
 * @author gui
 *
 */
abstract class G_Date_Abstract
{
	/**
	 * If there are parts in the outputOrder array
	 * that are missing in the regex result then try
	 * a better outputOrder array
	 * Otherwise throw an exception
	 * 
	 * @var unknown_type
	 */
	static public $adaptIfMissingPart = true;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_inputString;
	
	/**
	 * Contains the regex that validates
	 * the urlString input
	 * 
	 * @var G_Date_Regex_String_...
	 */
	private $_regex;
	
	/**
	 * This tells getOutputArray() how to rearrange
	 * the date parts
	 * 
	 * @var unknown_type
	 */
	private $_outputOrder;
	
	/**
	 * This contains the parts in the right
	 * order, to be used by toString()
	 * 
	 * @var unknown_type
	 */
	private $_outputArray;
	
	/**
	 * True when the regex is valid
	 * and there is something to output
	 * 
	 * @var unknown_type
	 */
	private $_isValid;
	
	/**
	 * Whenever a part is edited this should be false
	 * it will be true when $this->isValid() is called
	 * @var unknown_type
	 */
	private $_isValidated;
	
	/**
	 * This must be chaged by subclass
	 * if they want to force some parts to be present
	 * 
	 * @var unknown_type
	 */
	protected $_requiredParts;
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_outputString;
	
		/**
	 * What separates date part in toString();
	 * 
	 * @var unknown_type
	 */
	protected $_separator = '-';
	
	/**
	 * Populates the class members
	 * 
	 * @param unknown_type $url
	 * @return unknown_type
	 */
	public function __construct($inputString, $outputOrder = array(), $inputOrder = array())
	{
		$this->_setRequiredParts();
		//force subclasses to implement _setRequiredParts() correctly to avoid crash in getOutputArray
		if (null === $this->_requiredParts[G_Date_Regex_String::DAY] ||
			null === $this->_requiredParts[G_Date_Regex_String::MONTH] ||
			null === $this->_requiredParts[G_Date_Regex_String::YEAR]) {
			throw new G_Date_Exception('Error : _dayRequired _montRequired and _yearRequired must be set' . print_r($this, true));
		}
		//set the default output order if not passed in param
		if (empty($outputOrder)) {//default order
			$outputOrder = array(G_Date_Regex_String::DAY,
								 G_Date_Regex_String::MONTH,
								 G_Date_Regex_String::YEAR);
		}
		$this->setOutputOrder($outputOrder);
		//populate the regex object and allow the user to set an input order from construction
		$this->setRegex(new G_Date_Regex($inputString, new G_Date_Regex_String(null, $inputOrder)));
	}
	
	/**
	 * This will tell getOutputArray whether to throw exception
	 * or not when one of those is missing
	 * @return unknown_type
	 */
	abstract protected function _setRequiredParts();
	
	/**
	 * 
	 * @param array $order
	 * @return unknown_type
	 */
	public function setOutputOrder(array $order)
	{
		if (empty($order)) {
			throw new G_Date_Exception('Error : the outputOrder cannot be empty');
		}
		$count = count($order);
		for ($i = 0; $i < $count; $i++) {
			if (!isset($order[$i])) {
				throw new G_Date_Exception('Error : the outputOrder is no wellformed, it must be serially indexed, given : ' . print_r($order, true));
			}
		}
		//ensure class required parts are in the outputOrder array
		foreach ($this->_requiredParts as $partIdentifier => $required) {
			if (true === $required && !in_array($partIdentifier, $order)) {
				throw new G_Date_Exception('Error : the part is required from subclass and it is not available.');
			}
		}
		$this->_outputOrder = $order;
		$this->_isValidated = false;
		return $this;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getOutputOrder()
	{
		return $this->_outputOrder;
	}
	
	/**
	 * It will be valid only if the instance it has 
	 * something to output
	 * 
	 * When this is not valid
	 * try to see if $this->getRegex()->isValid()
	 * is not valid either. to see where the error
	 * came from
	 * 
	 * @return unknown_type
	 */
	public function isValid()
	{
		//if is valid is not set or it is not validated
		if (null === $this->_isValid || !$this->_isValidated) {
			//if the regex is valid see if there is something in outputArray
			if ($this->getRegex()->match()) {
				//if the output array is empty (because the part required for output is not in the parts available from regex)
				$arr = $this->getOutputArray();
				$this->_isValid = !empty($arr);
			} else { //otherwise this can't be valid either
				print_r($this->getRegex());
				$this->_isValid = false;
			}
			$this->_isValidated = true;
		}
		return $this->_isValid;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function isValidated()
	{
		return $this->_isValidated;
	}
	
	/**
	 * 
	 * @param $string
	 * @return unknown_type
	 */
	public function setSeparator($string)
	{
		$this->_separator = (string) $string;
		return $this;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getSeparator()
	{
		return $this->_separator;
	}
	
	/**
	 * If the class has something to output
	 * it will get the outputArray and render it
	 * as a string
	 * 
	 * @return unknown_type
	 */
	abstract public function toString();
	
	/**
	 * If the regex is not valid it throws up
	 * otherwise it will try to make the best possible
	 * outputArray given the available parts from the regex
	 * and the required parts by outputOrder
	 * 
	 * Whenever the outputOrder requires parts that are not
	 * available from regex it will adapt the outputOrder
	 * array by removing those parts (only when $adaptIfMissingPart === true)
	 * In that process of slicing the outputOrder array whenever
	 * a part is not available (with _adaptOrThrowException())
	 * it may empty the outputOrder array (if none of the parts 
	 * available from regex match the parts required by outputOrder).
	 * If this case arises, then the outputArray will be empty
	 * 
	 * @return unknown_type
	 */
	public function getOutputArray()
	{
		//return if already available
		if (null !== $this->_outputArray && true === $this->_isValidated) {
			return $this->_outputArray;
		}
		//ensure regex match succeed, throw exception otherwise
		if (!$this->getRegex()->match()) {
			throw new G_Date_Exception('Error : the date is not valid, or there was an error, use G_Data::isValid() method before calling this to avoid this error when it is not valid.' . print_r($this, true));
		}
		//prepare the output array that will be transformed to a string for return
		$this->_outputArray = array();
		//ensure all parts required for output are available in regex
		while (list($partPos, $partIdentifier) = each($this->_outputOrder)) {
			//if that part is not available from regex try to adapt the outputOrder (if allowed by user or design)
			if (null === $partValue = $this->getRegex()->getPart($partIdentifier)) {
				//!!!!important this will change the $this->_outputOrder array that is why a while loop is used instead of a foreach
				$this->_adaptOrThrowException($partPos);
			} else { //if the part is there then just add it to the outputArray
				$this->_outputArray[$partPos] = $partValue;
			}
		}
		return $this->_outputArray;
	}
	
	/**
	 * This will slice the part at $pos from the 
	 * outputOrder array
	 * 
	 * This may empty the outputOrder array
	 * it will cause getOutputArray() to create
	 * an empty outputArray
	 * 
	 * @param unknown_type $pos
	 * @return unknown_type
	 */
	private function _adaptOrThrowException($pos)
	{
		//if the user does not want to adapt when missing, or the part missing is required by design throw up
		if (false === self::$adaptIfMissingPart 					  ||
			true === $this->_requiredParts[$this->_outputOrder[$pos]]) {
			throw new G_Date_Exception('Error : the part that you required, is not available and it is not possible to adapt the output, if G_Date::$adaptIfMissingPart == false, you may want to change that. G_Date::$adaptIfMissingPart : ' . print_r(self::$adaptIfMissingPart, true));
		}
		//remove the portion of the outputOrder that corresponds to 
		//the part (so outputArray does not have that element)
		array_splice($this->_outputOrder, $pos, 1);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getYear()
	{
		return $this->getRegex()->getYear();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getMonth()
	{
		return $this->getRegex()->getMonth();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getDay()
	{
		return $this->getRegex()->getDay();
	}
	
	/**
	 * 
	 * @param G_Regex_String_Abstract $regex
	 * @return unknown_type
	 */
	public function setRegex(G_Regex_Abstract $regex)
	{
		$this->_regex = $regex;
		$this->_isValidated = false;
		return $this;
	}
	
	/**
	 * This is set since construction
	 * 
	 * @return unknown_type
	 */
	public function getRegex()
	{
		return $this->_regex;
	}

	/**
	 * Proxy
	 * 
	 * @return unknown_type
	 */
	public function getRegexString($iWillEdit = true)
	{
		$this->_isValidated = !$iWillEdit;
		return $this->_regex->getRegexStringObject((boolean) $iWillEdit);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getinputString()
	{
		return $this->getRegex()->getInputString();
	}
}