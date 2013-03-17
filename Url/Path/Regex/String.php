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
class G_Url_Path_Regex_String
extends G_Regex_String_Abstract
{
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_defaultRegex = '^(/(?:[^/]+/)*?)(?:([A_Za-z0-9\-_]+)\.([A-Za-z]{1,4}))?';
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_directory = '^(/(?:[^/]+/)*?)';
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_fileName = '([A_Za-z0-9\-_]+)';
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_fileExtension = '([A-Za-z]{1,4})';

	/**
	 * Only called 
	 * 
	 * (non-PHPdoc)
	 * @see Common/Regex/G_Regex_String_Abstract#getUpdatedRegex()
	 */
	final protected function _update()
	{
		$this->setRegex( $this->_directory . '(?:' . $this->_fileName . '\.' . $this->_fileExtension . ')?');
	}

}