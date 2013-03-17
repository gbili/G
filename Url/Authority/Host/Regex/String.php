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
class G_Url_Authority_Host_Regex_String
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
	protected $_defaultRegex = '((?:[^.]+\.)*?)?([A-Za-z0-9][A-Za-z0-9\-]+[A-Za-z0-9])\.([A-Za-z]{2,4})(?::(\d+))?$';

	/**
	 * @var unknown_type
	 */
	protected $_subdomains = '((?:[^.]*\.)*?)';
	
	/**
	 * @var unknown_type
	 */
	protected $_subdomainsOptional = true;
	
	/**
	 * authority cant be optional
	 * @var unknown_type
	 */
	protected $_sLDomain = '([A-Za-z0-9][A-Za-z0-9\-]+[A-Za-z0-9])';
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_tLDomain = '([A-Za-z]{2,4})';
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_port = '(?::(\d+))';

	/**
	 * 
	 * @var unknown_type
	 */
	protected $_portOptional = true;

	/**
	 * Only called 
	 * 
	 * (non-PHPdoc)
	 * @see Common/Regex/G_Regex_String_Abstract#getUpdatedRegex()
	 */
	final protected function _update()
	{
		if (true === self::$appendQuestionmarks) {
			$this->_subdomains .=  ((boolean) $this->_subdomainsOptional == true)? '?' : '';
			$this->_port .=  ((boolean) $this->_portOptional == true)? '?' : '';
		}
		$this->setRegex($this->_subdomains . $this->_sLDomain . '\.' .  $this->_tLDomain . $this->_port . '$');
	}

}