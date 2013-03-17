<?php
class G_Url_Authority_Regex
extends G_Regex_Abstract
{
	/**
	 * 
	 * @param unknown_type $input
	 * @param G_Url_Regex_String $regexStringObject
	 * @return unknown_type
	 */
	public function __construct($input, G_Url_Authority_Regex_String $regexStringObject)
	{
		parent::__construct($input, $regexStringObject);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasUserInfo()
	{
		return $this->hasGroupNumber(1);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getUserInfo()
	{
		return $this->getMatches(1);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getHost()
	{
		return $this->getMatches(2);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasPort()
	{
		return $this->hasGroupNumber(3);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getPort()
	{
		return $this->getMatches(3);
	}
}