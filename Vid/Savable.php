<?php
/**
 * Slimple Minimal Video data
 * @author gui
 *
 */
class G_Vid_Savable
extends G_Molecule_Savable
{
	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setPassTableNameToRequestor();
	}

	/**
	 * 
	 * @param unknown_type $title
	 * @return unknown_type
	 */
	public function setTitle(G_Vid_Title_Savable $title)
	{
		$this->_setElement('title', $title);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasTitle()
	{
		return $this->isSetKey('title');
	}
	
	/**
	 * 
	 * @param unknown_type $title
	 * @return unknown_type
	 */
	public function getTitle()
	{
		return $this->getElement('title');
	}
	
	/**
	 * 
	 * @param unknown_type $category
	 * @return unknown_type
	 */
	public function setCategory(G_Vid_Category_Savable $category)
	{
		$this->_setElement('category', $category);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasCategory()
	{
		return $this->isSetKey('category');
	}
	
	/**
	 * 
	 * @param unknown_type $category
	 * @return unknown_type
	 */
	public function getCategory()
	{
		return $this->getElement('category');
	}
	
	/**
	 * 
	 * @param unknown_type $tag
	 * @return unknown_type
	 */
	public function addTag(G_Vid_Tag_Savable $tag)
	{
		$this->_useKeyAsArrayAndPushValue('tag', $tag);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasTags()
	{
		return $this->isSetKey('tag');
	}
	
	/**
	 * 
	 * @param unknown_type $tag
	 * @return unknown_type
	 */
	public function getTags()
	{
		return $this->getElement('tag');
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
	public function setDate(G_Time_AgoToDate $date)
	{
		$this->_setElement('date', $date);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function setImage(G_Vid_Image_Savable $img)
	{
		$this->_setElement('image', $img);
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
	 * @return unknown_type
	 */
	public function setSource(G_Source_Savable $src)
	{
		$this->_setElement('src', $src);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getSource()
	{
		return $this->getElement('src');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasSource()
	{
		return $this->isSetKey('src');
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
}