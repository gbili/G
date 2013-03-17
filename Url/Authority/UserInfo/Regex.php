<?php
class G_Url_Authority_UserInfo_Regex
extends G_Regex_Abstract
{
	/**
	 * 
	 * @param unknown_type $input
	 * @param G_Url_Regex_String $regexStringObject
	 * @return unknown_type
	 */
	public function __construct($input, G_Url_Authority_UserInfo_Regex_String $regexStringObject)
	{
		parent::__construct($input, $regexStringObject);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getName()
	{
		return $this->getMatches(1);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getPass()
	{
		return $this->getMatches(2);
	}
}