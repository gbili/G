<?php
class G_Url_Authority_Host
extends G_Regex_Encapsulator_Abstract
{
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasSubdomains()
	{
		return $this->_hasPart('Subdomains');
	}

	/**
	 * 
	 * @param unknown_type $subdomains
	 * @return unknown_type
	 */
	public function setSubdomains($subdomains)
	{
		$this->_setPartWithDirtyData('Subdomains', $subdomains, false);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getSubdomains()
	{
		return $this->_getPart('Subdomains');
	}
	
	/**
	 * 
	 * @param unknown_type $sld
	 * @return unknown_type
	 */
	public function setSLDomain($sld)
	{
		$this->_setPartWithDirtyData('SLDomain', $sld, false);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getSLDomain()
	{
		return $this->_getPart('SLDomain');
	}
	
	/**
	 * 
	 * @param unknown_type $tld
	 * @return unknown_type
	 */
	public function setTLDomain($tld)
	{
		$this->_setPartWithDirtyData('TLDomain', $tld, false);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getTLDomain()
	{
		return $this->_getPart('TLDomain');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Url/G_Url_Abstract#_setParts()
	 */
	protected function _setParts()
	{
		if ($this->getRegex()->hasSubdomains()) {
			$this->_setPart('Subdomains', $this->getRegex()->getSubdomains(), false);
		}
		$this->_setPart('SLDomain', $this->getRegex()->getSLDomain(), false);
		$this->_setPart('TLDomain', $this->getRegex()->getTLDomain(), false);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	protected function _toString()
	{
		return (string) (($this->hasSubdomains())? $this->getSubdomains() : '') . $this->getSLDomain() . '.' . $this->getTLDomain();
	}
}