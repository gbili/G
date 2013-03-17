<?php
class G_Regex_String_LangISO extends G_Regex_String_Abstract
{
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_defaultRegex = '^(en|de|fr|it|pt|eu|ca|da)$';
	
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