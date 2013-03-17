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
class G_Url_Authority_Regex_String
extends G_Regex_String_Abstract
{

	/**
	 * 
	 * @var unknown_type
	 */
	protected $_defaultRegex = '([^:@.]+:[^:@.]+@)?([A-Za-z0-9.-]{5,})(?::(\d+))?$';
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_userInfo = '([^:@.]+:[^:@.]+@)';
	
	/**
	 * authority cant be optional
	 * @var unknown_type
	 */
	protected $_host = '([A-Za-z0-9.-]{5,})';
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_port = '(?::(\d+))';

	/**
	 * Only called 
	 * 
	 * (non-PHPdoc)
	 * @see Common/Regex/G_Regex_String_Abstract#getUpdatedRegex()
	 */
	final protected function _update()
	{
		$this->setRegex($this->_userInfo . '?' . $this->_host . $this->_port . '?' . '$');
	}

}