<?php
class G_Date_English
extends G_Date_Abstract
{
	/**
	 * 
	 * @param unknown_type $inputString
	 * @param unknown_type $outputOrder
	 * @return unknown_type
	 */
	public function __construct($inputString, $outputOrder = array(), array $inputOrder = array())
	{
		//dont allow to change the output order
		if (!empty($outputOrder)) {
			throw new G_Date_English_Exception('Error : you are not allowed to modify the output|input order, use G_Date if you want to change output order');
		}
		//use the english style output order
		parent::__construct($inputString, 
							array(G_Date_Regex_String::DAY,
								  G_Date_Regex_String::MONTH,
								  G_Date_Regex_String::YEAR),
							array(G_Date_Regex_String::DAY,
								  G_Date_Regex_String::MONTH,
								  G_Date_Regex_String::YEAR));
	}

	/**
	 * (non-PHPdoc)
	 * @see G_Date#_setRequiredParts()
	 */
	protected function _setRequiredParts()
	{
		$this->_requiredParts[G_Date_Regex_String::DAY]   = true;
		$this->_requiredParts[G_Date_Regex_String::MONTH] = true;
		$this->_requiredParts[G_Date_Regex_String::YEAR]  = true;
	}
	
	/**
	 * @todo needs to adapt the separators to the numbers ex: 1st, 2nd, 3rd, 4th, 5th ...
	 * @todo needs to match a month to a month in english ex: 12 -> december, janvier -> january
	 * @todo needs to adapt the year to an english year   ex: 98 -> 1998, 09 -> 2009
	 * (non-PHPdoc)
	 * @see G_Date#toString()
	 */
	public function toString()
	{
		//return if already available
		if (null !== $this->_outputString) {
			return $this->_outputString;
		}
		$outputArray = $this->getOutputArray();
		return $this->_outputString = $outputArray[0] . 'th ' . $outputArray[1] . ' ' . $outputArray[2];
	}
}