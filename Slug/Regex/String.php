<?php
/**
 * This class is will generate the regex pattern to match a date
 * There are a lot of things to rethink like the fact that the 3 parts
 * of the date must be set
 * 
 * 
 * @see G_Date_Regex for the implementation in a preg_match function
 * 
 * @author gui
 *
 */
class G_Slug_Regex_String extends G_Regex_String_Abstract
{
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_defaultRegex = '^([A-Za-z0-9]+(?:[A-Za-z0-9-]+[A-Za-z0-9])?)$';
	
	/**
	 * Only called 
	 * 
	 * (non-PHPdoc)
	 * @see Common/Regex/G_Regex_String_Abstract#getUpdatedRegex()
	 */
	protected function _update()
	{
	}
}