<?php
class G_Regex_String_Attribute extends G_Regex_String_Abstract
{
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_defaultRegex = '.*?=("??)([^" >=]*?)\\1';
	
	/**
	 * Only called 
	 * 
	 * (non-PHPdoc)
	 * @see Common/Regex/G_Regex_String_Abstract#getUpdatedRegex()
	 */
	private function _update()
	{
	}
}