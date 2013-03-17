<?php
/**
 * 
 * @author gui
 *
 */
class G_Kinship
{
	/**
	 * 
	 * @var unknown_type
	 */
	private $_chainedObject;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_parent;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_childrenArray = array();
	
	/**
	 * 
	 * @param unknown_type $chainedObject
	 * @return unknown_type
	 */
	public function __construct($chainedObject)
	{
		$this->_chainedObject = $chainedObject;
	}
	
	/**
	 * 
	 * @param G_Kinship $child
	 * @return unknown_type
	 */
	public function addChild(G_Kinship $child, $andSetItsParent = true)
	{
		$this->_childrenArray[] = $child;
		if (true === $andSetItsParent) {
			$child->setParent($this, false);
		}
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getNextChild()
	{
		if ($this->hasChildren()) {
			throw new G_Kinship_Exception('Child not set');
		}
		if (!($array = each($this->_childrenArray))) {
			reset($this->_childrenArray);
			return false;
		} else {
			return $array[1]; //return the value of the each result
		}
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getChildren()
	{
		return $this->_childrenArray;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasChildren()
	{
		return empty($this->_childrenArray);
	}
	
	/**
	 * 
	 * @param G_Kinship $parent
	 * @return unknown_type
	 */
	public function setParent(G_Kinship $parent, $andSetItsChild = true)
	{
		$this->_parent = $parent;
		if (true === $andSetItsChild) {
			$this->_parent->addChild($this, false);
		}
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getParent()
	{
		if (!$this->hasParent()) {
			throw new G_Kniship_Exception('Parent not set');
		}
		return $this->_parent;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasParent()
	{
		return (null !== $this->_parent);
	}

	/**
	 * 
	 * @param G_Kinship $brother
	 * @return unknown_type
	 */
	public function addBrother(G_Kinship $brother)
	{
		$this->getParent()->addChild($brother);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getBrothers()
	{
		return $this->getParent()->getChildren();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getNextBrother()
	{
		return $this->getParent()->getNextChild();
	}
}