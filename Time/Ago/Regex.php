<?php
class G_Time_Ago_Regex
extends G_Regex_Abstract
{
	/**
	 * 
	 * @param unknown_type $input
	 * @param G_Url_Regex_String $regexStringObject
	 * @return unknown_type
	 */
	public function __construct($input, G_Time_Ago_Regex_String $regexStringObject)
	{
		parent::__construct($input, $regexStringObject);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getNumber()
	{
		return $this->getMatches(1);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasYears()
	{
		return $this->hasGroupNumber(2);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasMonths()
	{
		return $this->hasGroupNumber(3);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasDays()
	{
		return $this->hasGroupNumber(4);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasHours()
	{
		return $this->hasGroupNumber(5);
	}
}