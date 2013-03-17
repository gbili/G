<?php
class G_Regex_String_Anchor extends G_Regex_String_Abstract
{
	/**
	 * Contains the very default regex
	 * 
	 * @var unknown_type
	 */
	protected $_defaultRegex = '<a\s[^>]*href=("??)([^" >]*?)\\1[^>]*>(.*?)<\/a>';
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_urlRegex;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_defaultUrlRegex = '[^" >]*?';
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_textRegex;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_defaultTextRegex = '.*?';
	
	/**
	 * 
	 * @param string $urlRegex
	 * @param string $textRegex
	 * @return null
	 */
	public function __construct($urlRegex = null, $textRegex = null)
	{
		if (null !== $urlRegex) {
			$this->setUrlRegex($urlRegex);
		} 
		if (null !== $textRegex) {
			$this->setTextRegex($textRegex);
		}
	}

	/**
	 * If no argument set url to accept everything
	 * 
	 * @return unknown_type
	 */
	public function setUrlRegex($urlRegex)
	{
		if ($urlRegex instanceof G_Url_Regex_String) {
			$urlRegex = $urlRegex->getRegex();
		}
		if (!is_string($urlRegex)) {
			throw new G_Regex_String_Exception('Error : The parameter must be a string or a G_Url_Regex_String Instance, given : ' . print_r($urlRegex, true));
		}
		$this->_urlRegex = $urlRegex;
		//we want getRegex() to include the new url
		//when it calls _update()
		$this->_setAsNotUpToDate();
		return $this;
	}

	/**
	 * 
	 * @return unknown_type
	 */
	public function getUrlRegex()
	{
		if (null === $this->_urlRegex) {
			$this->setUrlRegex($this->_defaultUrlRegex);
		}
		return $this->_urlRegex;
	}

	/**
	 * 
	 * @param unknown_type $textRegex
	 * @return unknown_type
	 */
	public function setTextRegex($textRegex)
	{
		if (!is_string($textRegex)) {
			throw new G_Regex_String_Exception('Error : The parameter must be a string given : ' . print_r($textRegex, true));
		}
		$this->_textRegex = $textRegex;
		$this->_setAsNotUpToDate();
		return $this;
	}

	/**
	 * 
	 * @return unknown_type
	 */
	public function getTextRegex()
	{
		if (null === $this->_textRegex) {
			$this->setTextRegex($this->_defaultTextRegex);
		}
		return $this->_textRegex;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	private function _update()
	{
		return $this->setRegex("<a\s[^>]*href=(\"??)({$this->getUrlRegex()})\\1[^>]*>({$this->getTextRegex()})<\/a>");
	}
}