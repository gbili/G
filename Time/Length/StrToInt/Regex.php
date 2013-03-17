<?php
class G_Time_Length_StrToInt_Regex
extends G_Regex_Abstract
{
	/**
	 * 
	 * @param unknown_type $input
	 * @param G_Url_Regex_String $regexStringObject
	 * @return unknown_type
	 */
	public function __construct($input, G_Time_Length_StrToInt_Regex_String $regexStringObject)
	{
		parent::__construct($input, $regexStringObject);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getHours()
	{
		return $this->getMatches(1);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasHours()
	{
		return $this->hasGroupNumber(1);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getMinutes()
	{
		return $this->getMatches(2);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasMinutes()
	{
		return $this->hasGroupNumber(2);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getSeconds()
	{
		return $this->getMatches(3);
	}
}