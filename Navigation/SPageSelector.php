<?php
/**
 * 
 * @author gui
 *
 */
class G_Navigation_SPageSelector
{

	/**
	 * Current Page Number
	 * 
	 * @var unknown_type
	 */
	private $_currPageNum = null;
	
	/**
	 * total Items In Book
	 * 
	 * @var unknown_type
	 */
	private $_totalIIB = null;
	
	/**
	 * Number of Items Per Page
	 * 
	 * @var unknown_type
	 */
	private $_nIPP = 6;
	
	/**
	 * Number of Buttons In Nav
	 * 
	 * @var unknown_type
	 */
	private $_nBIN = 5;
	
	/**
	 * Class used for css rendering
	 * 
	 * @var unknown_type
	 */
	private $_cssSUPClass = array('this_page', 'other_page');
	
	/**
	 * 
	 * @var unknown_type
	 */
	static private $_defaultSprintfFormat = '/page/%s/ipp/%s';
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_sprintfFormat = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_prepUri = '';
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		
	}
	
	/**
	 * 
	 * @param unknown_type $n
	 * @return unknown_type
	 */
	public function setCurrentPageNumber($n)
	{
		$this->_currPageNum = $n;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getCurrentPageNumber()
	{
		if (null === $this->_currPageNum) {
			throw new G_Exception('current page number must be set through setCurrentPageNumber($n)');
		}
		return $this->_currPageNum;
	}

	/**
	 * 
	 * @param unknown_type $n
	 * @return unknown_type
	 */
	public function setNumberItemsInBook($n)
	{
		if (0 > $n) {
			throw new G_Exception('The number of items in book cannot be less than 0');
		}
		$this->_totalIIB = $n;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getNumberItemsInBook()
	{
		if (null === $this->_totalIIB) {
			throw new G_Exception('the total number of items in book must be set with : setNumberItemsInBook($n)');
		}
		return $this->_totalIIB;
	}
	
	/**
	 * 
	 * @param unknown_type $n
	 * @return unknown_type
	 */
	public function setNumberOfItemsPerPage($n)
	{
		if (0 >= $n) {
			throw new G_Exception('The number of items per page cannot be less than or equal to 0');
		}
		$this->_nIPP = $n;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getNumberOfItemsPerPage()
	{
		return $this->_nIPP;
	}
	
	/**
	 * 
	 * @param unknown_type $n
	 * @return unknown_type
	 */
	public function setNumberOfButtonsInNav($n)
	{
		$this->_nBIN = $n;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getNumberOfButtonsInNav()
	{
		return $this->_nBIN;
	}
	
	/**
	 * 
	 * @param unknown_type $str
	 * @return unknown_type
	 */
	public function setCssSUPageClasses($selectedClassStr, $unselectedClassStr)
	{
		$this->_cssSUPClasses = array($selectedClassStr, $unselectedClassStr);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getCssSUPageClasses()
	{
		return $this->_cssSUPClasses;
	}
	
	/**
	 * 
	 * @param $format
	 * @return unknown_type
	 */
	public function setSprintfFormat($format)
	{
		$this->_sprintfFormat = $format;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getSprintfFormat()
	{
		if (null === $this->_sprintfFormat) {
			$this->_sprintfFormat = self::$_defaultSprintfFormat;
		}
		return $this->_sprintfFormat;
	}
	
	/**
	 * 
	 * @param $format
	 * @return unknown_type
	 */
	static public function setDefaultSprintfFormat($format)
	{
		self::$_defaultSprintfFormat = $format;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	static public function getDefaultSprintfFormat()
	{
		return self::$_defaultSprintfFormat;
	}
	
	/**
	 * 
	 * @param unknown_type $str
	 * @return unknown_type
	 */
	public function setPrependedUri($str)
	{
		$this->_prepUri = $str;
	}
	
	/**
	 * 
	 * @param unknown_type $str
	 * @return unknown_type
	 */
	public function getPrependedUri($str)
	{
		return $this->_prepUri;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function render()
	{
		//make sure $this->_sprintfFormat has something
		$this->getSprintfFormat();
		$html = '';

		$totalAmountOfPages = ceil($this->getNumberItemsInBook() / $this->getNumberOfItemsPerPage());
		
		/*
		 * Only render navigator if there is more than one page
		 */
		if ($totalAmountOfPages <= 1) {
			return $html;
		}
		
		/*
		 * Determine whether to show the "<<.. <" (go to first, go to previous) portion of the navigation
		 * When ? -- Only if we are not on the first page
		 */
		if ($this->getCurrentPageNumber() > 1) {//if the user is viewing a page bigger than the first show <<.. link
			$html .= $this->_renderHtmlButton($this->_cssSUPClass[1], 1, '<<...');
			$html .= $this->_renderHtmlButton($this->_cssSUPClass[1], ($this->_currPageNum - 1), '<');
		}
		
		/*
		 * Render the buttons for each page: this portion of the navigation:
		 *  |  1  | |  2  | |  3  | |  4  | |  5  |
		 *  When ? -- In any situation
		 */
		$pagesLeft = $totalAmountOfPages - $this->_currPageNum;
		if ($this->_currPageNum <= ($this->_nBIN - 2) || $totalAmountOfPages <= $this->_nBIN) {//until <-cond is true
			$forStart = 1;
			$forCount = min($this->_nBIN, $totalAmountOfPages);//don't show more buttons than pages available		
		} else if ($pagesLeft >= $this->_nBIN) {//if there are more pages left than the number of buttons in nav + previous condition (we are not on the first pages)
			$forStart = $this->_currPageNum - 2;//render the nav with the currentPage button positioned two buttons from the left
			$forCount = $forStart + $this->_nBIN - 1;//show the buttons for the next x pages
			
		} else {//if we are on the last pages
			$forStart = ($totalAmountOfPages + 1) - $this->_nBIN;//but start counting so that there are allways the number of buttons per page in nav
			$forCount = $totalAmountOfPages;//show the rest of pages
		}
		
		for ($i=$forStart; $i<= $forCount; $i++) { 
			$html .= $this->_renderHtmlButton(((integer) $i === (integer) $this->_currPageNum)? $this->_cssSUPClass[0]: $this->_cssSUPClass[1], $i, $i);
		}
		
		/*
		 * Determine whether to render this protion "> ..>>" (go to next, go to end)
		 * When ? -- Only if we are not in the last page
		 */
		if ($this->_currPageNum < $totalAmountOfPages) {
			$html .= $this->_renderHtmlButton($this->_cssSUPClass[1], ($this->_currPageNum + 1), '>');
			$html .= $this->_renderHtmlButton($this->_cssSUPClass[1], $totalAmountOfPages, '...>>');
		}
		return $html;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	private function _renderHtmlButton($class, $pageNum, $value)
	{
		$url = sprintf($this->_sprintfFormat, $pageNum, $this->_nIPP);
		return "<div class=\"$class\"><a href=\"{$this->_prepUri}{$url}\">$value</a></div>";
	}
	
}