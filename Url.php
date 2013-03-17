<?php
/**
 * Valid url placeholder it will also divide the url
 * in a logical placeholder way : <scheme><subdomains><authority><path>
 * <url> : http://videos.spain.megaupload.com/path?to=file/?path
 * <scheme> : http
 * <subdomains> : videos.spain
 * <authority> : megaupload.com
 * <path> : /path?to=file/?path
 * 
 * for this version only full url are allowed.
 * there _must_ be a scheme and authority
 * 
 * @author gui
 *
 */
class G_Url
extends G_Regex_Encapsulator_Abstract
{
	
	/**
	 * Retruns a normalized url
	 * 
	 * @return unknown_type
	 */
	protected function _toString()
	{
		return $this->getScheme() . '://' . $this->getAuthority()->toString() . (($this->hasPath())? $this->getPath() : '');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasScheme()
	{
		return $this->_hasPart('Scheme');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getScheme()
	{
		return $this->_getPart('Scheme');
	}
	
	/**
	 * 
	 * @param unknown_type $scheme
	 * @return unknown_type
	 */
	public function setScheme($scheme)
	{
		$this->_setPartWithDirtyData('Scheme', $scheme, false);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getAuthority()
	{
		return $this->_getPart('Authority');
	}
	
	/**
	 * 
	 * @param unknown_type $authority
	 * @return unknown_type
	 */
	public function setAuthority($authority)
	{
		$this->_setPartWithDirtyData('Authority', $authority);
	}
	
	/**
	 * Proxy
	 * 
	 * @return unknown_type
	 */
	public function getHost()
	{
		return $this->getAuthority()->getHost();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasPath()
	{
		return $this->_hasPart('Path');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getPath()
	{
		return $this->_getPart('Path');
	}

	/**
	 * 
	 * @param unknown_type $path
	 * @return unknown_type
	 */
	public function setPath($path)
	{
		//the path must start with /
		if ('/' !== mb_substr($path, 0, 1)) {
			$path = '/' . $path;
		}
		$this->_setPartWithDirtyData('Path', $path, false);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Url/G_Url_Abstract#_setParts()
	 */
	protected function _setParts()
	{
		$this->_setPart('Scheme', (($this->hasScheme())? $this->_regex->getScheme() : 'http'), false);
		$this->_setPart('Authority', $this->_regex->getAuthority());
		if ($this->getRegex()->hasPath()) {
			$this->_setPart('Path', $this->_regex->getPath(), false);
		}
		
	}
}