<?php
/**
 * 
 * @author gui
 *
 */
class G_Participant_Role_Savable
extends G_Molecule_Savable
{
	/**
	 * 
	 * @param unknown_type $name
	 * @return unknown_type
	 */
	public function __construct($name)
	{
		parent::__construct();
		$this->_setElement('name', (string) $name);
		$slug = new G_Slug($name);
		if (! $slug->isValid()) {
			throw new G_Participant_Role_Savable($slug->getError());
		}
		$this->_setElement('slug', $slug);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getName()
	{
		return $this->getElement('name');
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