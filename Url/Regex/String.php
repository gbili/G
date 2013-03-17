<?php
/**
 * This class will create a url regular expression
 * 
 * When extending this class for specific urls remember
 * not to put optional capturing groups with a ? rather
 * set the specific part of the url ex : <subdomains>
 * to optional with : $_subdomainsOptional = true
 * or override $_appendQuestionmarks = false
 * 
 * What is the usfulness of this class?? : i dont see it
 * 
 * @author gui
 *
 */
class G_Url_Regex_String
extends G_Regex_String_Abstract
{
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_defaultRegex = '(?:(https?|s?ftp)://)?([A-Za-z0-9.@:-]{5,})(?:(/[^ ]*)|$)';
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_scheme = '(?:(https?|s?ftp)://)';
	
	/**
	 * authority cant be optional
	 * @var unknown_type
	 */
	protected $_authority = '([A-Za-z0-9.@:-]{5,})';
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_path = '(/[^ ]*)';

	/**
	 * Only called 
	 * 
	 * (non-PHPdoc)
	 * @see Common/Regex/G_Regex_String_Abstract#getUpdatedRegex()
	 */
	final protected function _update()
	{
		$this->setRegex( $this->_scheme . '?' . $this->_authority . '(?:$|' . $this->_path . ')');
	}

}