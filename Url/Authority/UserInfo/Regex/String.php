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
class G_Url_Authority_UserInfo_Regex_String 
extends G_Regex_String_Abstract
{
	/**
	 * If you dont want to append question marks set this to false
	 * @var unknown_type
	 */
	static public $appendQuestionmarks = true;

	/**
	 * 
	 * @var unknown_type
	 */
	protected $_defaultRegex = '([0-9A-Za-z]+):([0-9A-Za-z]+)@';
	
	/**
	 * @var unknown_type
	 */
	protected $_name = '([0-9A-Za-z]+)';
	
	/**
	 * @var unknown_type
	 */
	protected $_pass = '([0-9A-Za-z]+)';
	
	/**
	 * Only called 
	 * 
	 * (non-PHPdoc)
	 * @see Common/Regex/G_Regex_String_Abstract#getUpdatedRegex()
	 */
	final protected function _update()
	{
		$this->setRegex($this->_name . ':' . $this->_pass . '@');
	}

}