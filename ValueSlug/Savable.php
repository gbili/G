<?php
/**
 * Name and slug
 * 
 * Some entites just need to be identified
 * by their value, the slug, and an id
 * 
 * Just extend this if it is the case
 * 
 * @author gui
 *
 */
class G_ValueSlug_Savable
extends G_Molecule_Savable
{
	/**
	 * 
	 * @param unknown_type $value
	 * @return void
	 */
	public function __construct($value)
	{
		parent::__construct();
		//this will tell the parent to use this specified requestor
		//for all subclasses, unless they specify otherwise
		$this->setRequestorClassName('G_ValueSlug_Savable');
		//tell parent to tell requestor the table name
		$this->setPassTableNameToRequestor();
		
		$this->_setElement('value', (string) $value);
		$slug = new G_Slug($value);
		if (! $slug->isValid()) {
			throw new G_Molecule_Savable_Exception($slug->getError());
		}
		$this->_setElement('slug', $slug);
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getValue()
	{
		return $this->getElement('value');
	}
	
	/**
	 * 
	 * @return G_Slug
	 */
	public function getSlug()
	{
		return $this->getElement('slug');
	}
}