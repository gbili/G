<?php
/**
 * 
 * @author gui
 *
 */
class G_Source_Savable
extends G_Molecule_Savable
{
	/**
	 * 
	 * @param G_Url $url
	 * @return unknown_type
	 */
	public function __construct(G_Url $url)
	{
		parent::__construct();
		$this->setPassTableNameToRequestor();
		$this->_setElement('host', new G_Source_Host_Savable($url->getHost()));
		$this->_setElement('path', $url->getPath());
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getPath()
	{
		return $this->getElement('path');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getHost()
	{
		return $this->getElement('host');
	}
}