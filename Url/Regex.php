<?php
class G_Url_Regex
extends G_Regex_Abstract
{
	/**
	 * 
	 * @param unknown_type $input
	 * @param G_Url_Regex_String $regexStringObject
	 * @return unknown_type
	 */
	public function __construct($input, G_Url_Regex_String $regexStringObject)
	{
		parent::__construct($input, $regexStringObject);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getScheme()
	{
		return ($this->hasGroupNumber(1))? $this->getMatches(1) : 'http';
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getAuthority()
	{
		return mb_strtolower($this->getMatches(2));
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasPath()
	{
		return $this->hasGroupNumber(3);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getPath()
	{
		return $this->getMatches(3);
	}
}