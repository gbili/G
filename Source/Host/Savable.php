<?php
/**
 * 
 * @author gui
 *
 */
class G_Source_Host_Savable
extends G_Molecule_Savable
{
	/**
	 * 
	 * @param G_Url_Authority_Host $host
	 * @return unknown_type
	 */
	public function __construct(G_Url_Authority_Host $host)
	{
		parent::__construct();
		$this->setPassTableNameToRequestor();
		$this->_setElement('host', $host);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getHost()
	{
		return $this->getElement('host');
	}
	
	/**
	 * Human friendly name
	 * @param unknown_type $humanFriendlyName
	 * @return unknown_type
	 */
	public function setHFName($humanFriendlyName)
	{
		$this->_setElement('hFName', (string) $humanFriendlyName);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getHFName()
	{
		return $this->getElement('hFName');
	}
}