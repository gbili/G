<?php
class G_Url_Authority_Host_Regex
extends G_Regex_Abstract
{
	/**
	 * 
	 * @param unknown_type $input
	 * @param G_Url_Regex_String $regexStringObject
	 * @return unknown_type
	 */
	public function __construct($input, G_Url_Authority_Host_Regex_String $regexStringObject)
	{
		parent::__construct($input, $regexStringObject);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasSubdomains()
	{
		return $this->hasGroupNumber(1);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getSubdomains()
	{
		return $this->getMatches(1);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getSLDomain()
	{
		return $this->getMatches(2);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getTLDomain()
	{
		return $this->getMatches(3);
	}
}