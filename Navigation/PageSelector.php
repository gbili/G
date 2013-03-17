<?php
/**
 * Renders the side bar.
 * You have to check before passing data that the data exists in the database otherwise
 * it will be buggy.
 * 
 * This sidebar wine will render custom search side bar, meaning that it will append the
 * user search url params to each <a href="" thus allowing the application to query with
 * additional params on each request, and narrowing further the search
 * 
 * This works fine for the countries untill no country with more than one word is passed
 * so beware...If you want to solve the problem, add a country slug to the country table
 * and then call Model_Geo_Db_Gateway_Requestor()->countryExists(array(countrySlug=>'');
 * antd this will return the appropriate name.
 * 
 * You can also transform the helper and just haveit to render the data that you pass as
 * params
 * 
 * 
 * @author gui
 *
 */
class G_Navigation_PageSelector
{
	/**
	 * THe part of the ur that mus be prepended
	 * like "http://www.domain.com"
	 * 
	 * @var unknown_type
	 */
	static private $_rUPS = null;
	
	/**
	 * The parameter key name in url
	 * 
	 * @var unknown_type
	 */
	static private $_pagePN = 'p';
	
	/**
	 * the parameter key name in url
	 * 
	 * @var unknown_type
	 */
	static private $_itemsPerPagePN = 'ipp';
	
	/**
	 * 
	 * @var unknown_type
	 */
	static private $_isDispalyIPP = true;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_iPPHtml = '';
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_requestUrl = '';
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		
	}
	
	/**
	 * 
	 * @param unknown_type $authority
	 * @return unknown_type
	 */
	static public function setRequestUrlPrependSegment($segment)
	{
		self::$_rUPS = $segment;
	}
	
	/**
	 * page param name
	 * @param unknown_type $name
	 * @return unknown_type
	 */
	static public function setPPName($name)
	{
		self::$_pagePN = (string) $name;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	static public function getPPName()
	{
		return self::$_pagePN;
	}
	
	/**
	 * items per page param name
	 * 
	 * @param unknown_type $name
	 * @return unknown_type
	 */
	static public function setIPPName($name)
	{
		self::$_itemsPerPagePN = (string) $name;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	static public function getIPPName()
	{
		return self::$_itemsPerPagePN;
	}
	
	/**
	 * Will append the IPP param to the
	 * url. This will allow the client
	 * to change the ipp number.
	 * But if not in url, cannot use it
	 * in application, must be hardcoded
	 * 
	 * @return unknown_type
	 */
	static public function displayIPP($bool)
	{
		self::$_isDispalyIPP = (boolean) $bool;
	}
	
	/**
	 * This function will render a page selector with this layout:
	 * <<... <  ||  1  | |  2  | |  3  | | 4 | | 5 |  > ... >>
	 * 
	 * @param $currentPageNumber the page number that the user requested
	 * @param $totalAmountOfPages the total amount of pages available (amount of records in db divided by records per page)
	 * @param $requestUrl the url between the domain name and the page parameter (the request url must start with "/" and not end with "/")
	 * @param $iPP the number of items to display per page
	 * @param $numberOfButtonsInNav how many buttons to show in the navigation (must be at least 5)
	 * @return unknown_type
	 */
	public function pageSelector($requestUrl, $totalAmountOfItems = 0, $currentPageNumber = 1, $iPP = 10, $numberOfButtonsInNav = 5)
	{	
		
		//init html
		$html = '';
		//allow a part of the url to be prepended automatically
		$this->_requestUrl = (string) ((null !== self::$_rUPS)? self::$_rUPS : '') . $requestUrl;
		// /ipp/123 or empty string
		$this->_iPPHtml = (true !== self::$_isDispalyIPP)? '' : self::$_itemsPerPagePN . '/' . $iPP;
		
		$totalAmountOfPages = ceil($totalAmountOfItems / $iPP);
		
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
		if ($currentPageNumber > 1) {//if the user is viewing a page bigger than the first show <<.. link
			$html .= $this->_renderHtmlPart('other_page', 1, '<<...');
			$html .= $this->_renderHtmlPart('other_page', ($currentPageNumber - 1), '<');
		}
		
		/*
		 * Render the buttons for each page: this portion of the navigation:
		 *  |  1  | |  2  | |  3  | |  4  | |  5  |
		 *  When ? -- In any situation
		 */
		$pagesLeft = $totalAmountOfPages - $currentPageNumber;
		if ($currentPageNumber <= ($numberOfButtonsInNav - 2) || $totalAmountOfPages <= $numberOfButtonsInNav) {//until <-cond is true
			$forStart = 1;
			$forCount = min($numberOfButtonsInNav, $totalAmountOfPages);//don't show more buttons than pages available		
		} else if ($pagesLeft >= $numberOfButtonsInNav) {//if there are more pages left than the number of buttons in nav + previous condition (we are not on the first pages)
			$forStart = $currentPageNumber - 2;//render the nav with the currentPage button positioned two buttons from the left
			$forCount = $forStart + $numberOfButtonsInNav - 1;//show the buttons for the next x pages
			
		} else {//if we are on the last pages
			$forStart = ($totalAmountOfPages + 1) - $numberOfButtonsInNav;//but start counting so that there are allways the number of buttons per page in nav
			$forCount = $totalAmountOfPages;//show the rest of pages
		}
		
		for ($i=$forStart; $i<= $forCount; $i++) { 
			$html .= $this->_renderHtmlPart(((integer) $i === (integer) $currentPageNumber)? 'curr_page': 'other_page', $i, $i);
		}
		
		/*
		 * Determine whether to render this protion "> ..>>" (go to next, go to end)
		 * When ? -- Only if we are not in the last page
		 */
		if ($currentPageNumber < $totalAmountOfPages) {
			$html .= $this->_renderHtmlPart('other_page', ($currentPageNumber + 1), '>');
			$html .= $this->_renderHtmlPart('other_page', $totalAmountOfPages, '...>>');
		}
		return $html;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	private function _renderHtmlPart($class, $page, $value)
	{
		return '<div class="' . $class . '"><a href="' . $this->_requestUrl . '/' . self::$_pagePN . "/$page/$this->_iPPHtml\">$value</a></div>";
	}
	
}