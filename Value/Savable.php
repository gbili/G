<?php
/**
 * 
 * @author gui
 *
 */
class G_Value_Savable
extends G_Molecule_Savable
{
	/**
	 * 
	 * @param unknown_type $value
	 * @return unknown_type
	 */
	public function __construct($value)
	{
		parent::__construct();
		$this->setRequestorClassName('G_Value_Savable');
		$this->setPassTableNameToRequestor();
		$this->_setElement('value', $value);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getValue()
	{
		return $this->getElement('value');
	}
}