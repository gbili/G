<?php
/**
 * Extend the parent functionality by 
 * adding a category filter
 * 
 * @author gui
 *
 */
class G_VLs_Filter_Vid
extends G_VLs_Filter
{
	/**
	 * @var unknown_type
	 */
	private $_catSlug = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	static private $_defaultIPP = 50;
	
	public function __construct($pageNum = null, $regex = null)
	{
		parent::__construct($pageNum, $regex);
		$this->_iPP = self::$_defaultIPP;
		$this->_orderBy = G_VLs_Filter::DATE;
	}
	
	/**
	 * 
	 * @param int $n
	 * @return unknown_type
	 */
	static public function setDefaultIPP($n)
	{
		self::$_defaultIPP = (integer) $n;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	static public function getDefaultIPP()
	{
		return self::$_defaultIPP;
	}
	
	/**
	 * 
	 * @param G_Slug $c
	 * @return unknown_type
	 */
	public function setCatSlug($c)
	{
		if (false === G_Slug::isSlug($c)) {
			throw new G_VLs_Filter_Exception('the catSlug must be a valid slug string');
		}
		$this->_catSlug = $c;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasCatSlug()
	{
		return null !== $this->_catSlug;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getCatSlug()
	{
		return $this->_catSlug;
	}
	
}