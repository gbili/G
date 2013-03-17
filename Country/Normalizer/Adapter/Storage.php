<?php
/**
 * 
 * @author gui
 *
 */
class G_Country_Normalizer_Adapter_Db
extends G_Country_Normalizer_Adapter_Abstract
{	
	/**
	 * Retrieves all records at once
	 * 
	 * @var unknown_type
	 */
	const MODE_FETCH_ALL = 1234;
	
	/**
	 * Retrieves the records in block
	 * the size depends on the value of
	 * self::$maxRowsBeforeFractionning;
	 * 
	 * @var unknown_type
	 */
	const MODE_FETCH_BLOCKS = 2345;
	
	/**
	 * Query db each time getNext() is
	 * called
	 * 
	 * @var unknown_type
	 */
	const MODE_FETCH_ONE = 3456;
	
	/**
	 * Tells the number of rows that
	 * a query can retrieve when
	 * self::$mode == MODE_FETCH_BLOCKS
	 * @var unknown_type
	 */
	static public $blockMaxSize = 15;
	
	/**
	 * The mode in which this class
	 * fetches the databse
	 * 
	 * @var unknown_type
	 */
	static private $_mode;

	/**
	 * Tells the number of queries
	 * that have been made against db
	 * 
	 * @var unknown_type
	 */
	private $_fetchCount;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_Db;

	/**
	 * Contains the db array of
	 * the country matchers
	 * ex : 
	 * 	array(1=>array('name' = 
	 * 					)
	 * 	)
	 * 
	 * @var unknown_type
	 */
	private $_countryMatchersArray;

	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_Db = G_Db_Registry::getInstance('G_Country_Normalizer_Adapter');
		if (null === self::$_mode) {
			self::$_mode = self::MODE_FETCH_BLOCKS;
		}
		$this->reset();
	}
	
	/**
	 * 
	 * @param unknown_type $countryStr
	 * @return unknown_type
	 */
	public function saveDirtyCountryStr($countryStr)
	{
		if (false === $id = $this->_Db->saveDirtyCountryStr($countryStr)) {
			throw new G_Country_Normalizer_Adapter_Exception('No id returned by save dirty countr str' . $countryStr);
		}
		return $id;
	}
	
	/**
	 * 
	 * 
	 * This function can be implemented
	 * in many different ways:
	 * 	1. query the database once
	 * 	   and retrieve all the records
	 *     then get next would only loop
	 *     through the retrieved records
	 *     until Abstract finds the match
	 * 	
	 * 	2. query the database for a
	 *     first set of common countries
	 *     and loop through them, if the
	 *     Abstract class doesn't find a
	 *     match, the query a second set,
	 *     do this until it matches
	 *     
	 *  3. query the database each thime
	 *     Abstract class wants a new 
	 *     record.
	 * 
	 * with in a way where there may be
	 * queries 
	 * 
	 * @return unknown_type
	 */
	public function getNext()
	{
		//if there are no elements in array
		if (empty($this->_countryMatchersArray)) {
			// fetch elements from db
			$this->_fillCMArray();
			//if there is nothing more in database
			if (false === $this->_countryMatchersArray) {
				//let abstract know that there are no more
				return false;
			}
		}
		//countrymatchersarray is not false so it didn't return
		//as it is not false it may contain an array
		//however if pointer in array reached end
		if (false === list($key, $element) = each($this->_countryMatchersArray)) {
			//fetch elements from db
			$this->_fillCMArray();
			//if there is nothing more in database
			if (false === $this->_countryMatchersArray) {
				//let abstract know that there are no more
				return false;
			}
			//if reached here, there is something in array
			//and the pointer is different than null
			list($key, $element) = each($this->_countryMatchersArray);
		}
		//reformat db row to abstract understandable
		return $this->reformatRow($element);
	}

	/**
	 * Will put an array or false in $_countryMatchersArray
	 * 
	 * @return unknown_type
	 */
	private function _fillCMArray()
	{
		//adapt query to mode
		switch  (self::$_mode) {
			case self::MODE_FETCH_BLOCKS;
					$this->_countryMatchersArray = $this->_Db->getBlock($this->_fetchCount);
				break;
			case self::MODE_FETCH_ALL;
					$this->_countryMatchersArray = $this->_Db->getAll();
				break;
			case self::MODE_FETCH_ONE;
					$this->_countryMatchersArray = $this->_Db->getOne($this->_fetchCount);
				break;
			default;
					throw new G_Country_Normalizer_Adapter_Exception('Error : You must also put case in switch for _getSql() when adding a new mode or use the generic mode MODE_FETCH_ALL if you don\'t need to edit the sql.');
				break;
		}
		if ($this->_countryMatchersArray !== false){//there are results from query
			//increase fetch count
			$this->_fetchCount++;
		}
	}
	
	/**
	 * Converts the database row array
	 * to a abstract understandable array
	 * of a country matcher
	 * 
	 * @param array $rowArray
	 * @return unknown_type
	 */
	public function reformatRow(array $rowArray)
	{
		$countryName = array_shift($rowArray);
		$return = array();
		$return[$countryName] = array('regex' 	=> $rowArray['regex'],
						  			  'langISO' => explode(',', $rowArray['langISO']),
									  'id'		=> $rowArray['countryId']);
		return $return;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see International/Country/Normalizer/Adapter/G_Country_Normalizer_Adapter_Abstract#reset()
	 */
	public function reset()
	{
		$this->_countryMatchersArray = array();
		$this->_fetchCount = 0;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see International/Country/Normalizer/Adapter/G_Country_Normalizer_Adapter_Abstract#getCountries()
	 */
	public function getCountries()
	{
		if (false === $countriesArray = $this->_Db->getCountries()) {
			throw new G_Country_Normalizer_Adapter_Exception('Error : Db does not have any country');
		}
		return $countriesArray;
	}
	
	/**
	 * 
	 * (non-PHPdoc)
	 * @see International/Country/Normalizer/Adapter/G_Country_Normalizer_Adapter_Abstract#getLangISOFromNormalizedCountry($normalizedCountryStr)
	 */
	public function getLangISOFromNormalizedCountry($countryStr)
	{
		$langsStr = $this->_Db->getCountryLangISO($countryStr);
		//if there are no results
		if (false === $langsStr) {
			//ensure the country is normalized|supported
			if ($this->_Db->isSupportedCountry($countryStr)) {
				throw new G_Country_Normalizer_Adapter_Exception('Error : the passed country str apears not to be normalized or the country is not supported given :' . print_r($countryStr, true));
			}
		}
		return explode(',', $langsStr);//'en,fr,de' -> array('en', 'fr', 'de')
	}
	
	/**
	 * (non-PHPdoc)
	 * @see International/Country/Normalizer/Adapter/G_Country_Normalizer_Adapter_Abstract#isNormalizedCountryStr($dirtyCountryStr)
	 */
	public function isNormalizedCountryStr($dirtyCountryStr)
	{
		return $this->_Db->isSupportedCountry($dirtyCountryStr);
	}
	
	/**
	 * Set the way this class will fetch database
	 * 
	 * @param $mode
	 * @return unknown_type
	 */
	static public function setMode($mode)
	{
		//ensure mode is supported
		switch  ($mode) {
			case self::MODE_FETCH_BLOCKS;
				break;
			case self::MODE_FETCH_ALL;
				break;
			case self::MODE_FETCH_ONE;
				break;
			default;
					throw new G_Country_Normalizer_Adapter_Exception('Error : The mode is not supported, given: ' . print_r($mode,true));
				break;
		}
		self::$_mode = $mode;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	static public function getMode()
	{
		return self::$_mode;
	}
}