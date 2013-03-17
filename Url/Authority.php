<?php
class G_Url_Authority
extends G_Regex_Encapsulator_Abstract
{
	/**
	 * (non-PHPdoc)
	 * @see Url/G_Url_Abstract#toString()
	 */
	protected function _toString()
	{
		return (($this->hasUserInfo())? $this->getUserInfo()->toString() : '') . $this->getHost()->toString() . (($this->hasPort())? ':' . $this->getPort() : '');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasUserInfo()
	{
		return $this->_hasPart('UserInfo');
	}
	
	/**
	 * 
	 * @param $userInfo
	 * @return unknown_type
	 */
	public function setUserInfo($userInfo)
	{
		$this->_setPartWithDirtyData('UserInfo', $userInfo);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getUserInfo()
	{
		return $this->_getPart('UserInfo');		
	}
	
	/**
	 * 
	 * @param unknown_type $hostName
	 * @return unknown_type
	 */
	public function setHost($hostName)
	{
		$this->_setPartWithDirtyData('Host', $hostName);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getHost()
	{
		return $this->_getPart('Host');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasPort()
	{
		return $this->_hasPart('Port');
	}
	
	/**
	 * 
	 * @param unknown_type $port
	 * @return unknown_type
	 */
	public function setPort($port)
	{
		$this->_setPartWithDirtyData('Port', $port, false);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getPort()
	{
		return $this->_getPart('Port');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Url/G_Url_Abstract#_setParts()
	 */
	protected function _setParts()
	{
		if ($this->getRegex()->hasUserInfo()) {
			$this->_setPart('UserInfo', $this->getRegex()->getUserInfo());
		}
		$this->_setPart('Host', $this->getRegex()->getHost());
		if ($this->getRegex()->hasPort()) {
			$this->_setPart('Port', $this->getRegex()->getPort(), false);
		}	
	}
}