<?php
/**
 * MIE stands for Movie Industry Entity which are 
 * all entities (person|studio) related in the process
 * of making a movie.
 * 
 * They are subdivided into categories into
 * G_Participant :
 * 	-actor
 * 	-producer
 * 	-director
 * 
 * @author gui
 *
 */
class G_MIE_Savable
extends G_Molecule_Savable
{	
	/**
	 * 
	 * @param $name
	 * @return unknown_type
	 */
	public function __construct($name)
	{
		parent::__construct();
		$this->_setElement('name', (string) $name);
		$slug = new G_Slug($name);
		if (! $slug->isValid()) {
			throw new G_MIE_Exception($slug->getError());
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
	 * @return unknown_type
	 */
	public function getSlug()
	{
		return $this->getElement('slug');
	}
	
	//public function setCountry()
}