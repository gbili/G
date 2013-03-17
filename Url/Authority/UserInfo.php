<?php
class G_Url_Authority_UserInfo
extends G_Regex_Encapsulator_Abstract
{
	/**
	 * 
	 * @param unknown_type $username
	 * @return unknown_type
	 */
	public function setName($username)
	{
		$this->_setPartWithDirtyData('Name', $username, false);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getName()
	{
		return $this->_getPart('Name');
	}
	
	/**
	 * 
	 * @param unknown_type $password
	 * @return unknown_type
	 */
	public function setPass($password)
	{
		$this->_setPartWithDirtyData('Pass', $password, false);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getPass()
	{
		return $this->_getPart('Pass');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Url/G_Url_Abstract#_setParts()
	 */
	protected function _setParts()
	{
		$this->_setPart('Name', $this->getRegex()->getName(), false);
		$this->_setPart('Pass', $this->getRegex()->getPass(), false);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Url/G_Url_Abstract#toString()
	 */
	protected function _toString()
	{
		return (string) $this->getName() . ':' . $this->getPass() . '@';
	}
}