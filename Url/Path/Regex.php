<?php
/**
 * 
 * @author gui
 *
 */
class G_Url_Path_Regex
extends G_Regex_Abstract
{
	/**
	 * 
	 * @param unknown_type $input
	 * @param G_Url_Regex_String $regexStringObject
	 * @return unknown_type
	 */
	public function __construct($input, G_Url_Path_Regex_String $regexStringObject)
	{
		parent::__construct($input, $regexStringObject);
	}
	
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getDirectory()
	{
		return $this->getMatches(1);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasFileName()
	{
		return $this->hasGroupNumber(2);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getFileName()
	{
		return $this->getMatches(2);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasFileExtension()
	{
		return $this->hasGroupNumber(3);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getFileExtension()
	{
		return $this->getMatches(3);
	}
}