<?php
/**
 * Contains info shared among
 * G_Video_Entity_Savable(ies) of
 * the same Originla video entity
 * 
 * This class avoids data using a
 * lot of memory because of data
 * repetition
 * 
 * The question is: 
 * Should it extend G_Molecule_Abstract?
 * 
 * @author gui
 *
 */
class G_Video_Entity_SharedInfo_Savable
extends G_Molecule_Savable
{	
	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getDate()
	{
		return $this->getElement('date');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasDate()
	{
		return ($this->isSetKey('date'));
	}

	/**
	 * 
	 * @param G_Country $country
	 * @return unknown_type
	 */
	public function setDate(G_Date_Abstract $date)
	{
		if (!$date->isValid()) {
			throw new G_Video_Entity_Exception('date is not valid');
		}
		$this->_setElement('date', $date->toString());
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function setImage(G_Image_Savable $img)
	{
		$this->_setElement('image', $img);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function isUsingRecycledImage()
	{
		if (!$this->hasImage()) {
			throw new G_Video_Entity_Exception('There is no image for this shared info instance so cannot determine whether it is recycled or not');
		}
		//default value
		if (!$this->isSetKey('isUsingRecycledImage')) {
			$this->_setElement('isUsingRecycledImage', 0);
		}
		return $this->getElement('isUsingRecycledImage');
	}
	
	/**
	 * 
	 * @param unknown_type $boolean
	 * @return unknown_type
	 */
	public function setAsUsingRecycledImage()
	{
		$this->_setElement('isUsingRecycledImage', 1);
	}
	
	
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasImage()
	{
		return $this->isSetKey('image');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getImage()
	{
		return $this->getElement('image');
	}
	
	/**
	 * 
	 * @return G_Country
	 */
	public function getCountry()
	{
		return $this->getElement('country');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasCountry()
	{
		return $this->isSetKey('country');
	}

	/**
	 * 
	 * @param G_Country $country
	 * @return unknown_type
	 */
	public function setCountry(G_Country $country)
	{
		$this->_setElement('country', $country);
	}
	
	/**
	 * 
	 * @param G_Participant $participant
	 * @return unknown_type
	 */
	public function addParticipant(G_Participant_Savable $participant)
	{
		$this->_useKeyAsArrayAndPushValue('participants', $participant, G_Molecule_Savable::POST_SAVE_LOOP);
	}
	
	/**
	 * 
	 * @param unknown_type $genre
	 * @return unknown_type
	 */
	public function addGenre(G_Video_Entity_Genre_Savable $genre)
	{
		$this->_useKeyAsArrayAndPushValue('genre', $genre);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getGenres()
	{
		return $this->getElement('genre');
	}

	/**
	 * 
	 * @return unknown_type
	 */
	public function hasGenre()
	{
		return $this->isSetKey('genre');
	}

	/**
	 * 
	 * @param unknown_type $timeLength
	 * @return unknown_type
	 */
	public function setTimeLength($timeLength)
	{
		$this->_setElement('timeLength', $timeLength);
	}

	/**
	 * 
	 * @return unknown_type
	 */
	public function getTimeLength()
	{
		return $this->getElement('timeLength');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasTimeLength()
	{
		return $this->isSetKey('timeLength');
	}
	
	/**
	 * 
	 * @param unknown_type $originalTitle
	 * @return unknown_type
	 */
	public function setOriginalTitle(G_Video_Entity_Title_Savable $originalTitle)
	{
		$this->_setElement('originalTitle', $originalTitle);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getOriginalTitle()
	{
		return $this->getElement('originalTitle');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasOriginalTitle()
	{
		return $this->isSetKey('originalTitle');
	}
}