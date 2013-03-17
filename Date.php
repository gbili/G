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
class G_Date
extends G_Date_Abstract
{	
	/**
	 * Populates the class members
	 * 
	 * @param unknown_type $url
	 * @return unknown_type
	 */
	public function __construct($inputString, $outputOrder = array(), $inputOrder = array())
	{
		parent::__construct($inputString, $outputOrder, $inputOrder);
	}
	
	/**
	 * This will tell getOutputArray whether to throw exception
	 * or not when one of those is missing
	 * @return unknown_type
	 */
	protected function _setRequiredParts()
	{
		$this->_requiredParts[G_Date_Regex_String::DAY]   = false;
		$this->_requiredParts[G_Date_Regex_String::MONTH] = false;
		$this->_requiredParts[G_Date_Regex_String::YEAR]  = false;
	}
	
	/**
	 * If the class has something to output
	 * it will get the outputArray and render it
	 * as a string
	 * 
	 * @return unknown_type
	 */
	public function toString()
	{
		//return if already available
		if (null !== $this->_outputString && true === $this->isValidated()) {
			return $this->_outputString;
		}
		//ensure there is something to output
		if (!$this->isValid()) {
			throw new G_Date_Exception('Error : there is nothing to output probably because the inputOrder and outputOrder have no common parts, use the isValid() function before the toString() $this :' . print_r($this, true));
		}
		//otherwise get the output array and turn it into string
		$outputArray = $this->getOutputArray();
		$count = count($outputArray);
		if ($count > 1) {
			//if we used implode instead of the for loop it wont order the keys so the outputOrder won't be respected
			$this->_outputString = (string) $outputArray[0];
			for ($i = 1; $i < $count; $i++) {
				 $this->_outputString .= $this->_separator . $outputArray[$i];
			}
		} else { // == 1
			$this->_outputString = (string) $outputArray[0];
		}
		return $this->_outputString;
	}
}