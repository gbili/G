<?php
/**
 * 
 * @author gui
 *
 */
class G_Lang_ISO_Normalizer_Adapter_Db
extends G_Lang_ISO_Normalizer_Adapter_Abstract
{	
	/**
	 * Retrieves all records at once
	 * 
	 * @var unknown_type
	 */
	const MODE_FETCH_ALL = 12;
	
	/**
	 * Retrieves the records in block
	 * the size depends on the value of
	 * self::$maxRowsBeforeFractionning;
	 * 
	 * @var unknown_type
	 */
	const MODE_FETCH_BLOCKS = 23;
	
	/**
	 * Query db each time getNext() is
	 * called
	 * 
	 * @var unknown_type
	 */
	const MODE_FETCH_ONE = 34;
	
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
	 * the langISO matchers
	 * ex : 
	 * 	array(1=>array('langISO' = 
	 * 					)
	 * 	)
	 * 
	 * @var unknown_type
	 */
	private $_langISOMatchersArray;

	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_Db = G_Db_Registry::getInstance('G_Lang_ISO_Normalizer_Adapter');
		if (null === self::$_mode) {
			self::$_mode = self::MODE_FETCH_BLOCKS;
		}
		$this->reset();
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
		if (empty($this->_langISOMatchersArray)) {
			// fetch elements from db
			$this->_fillLMArray();
			//if there is nothing more in database
			if (false === $this->_langISOMatchersArray) {
				//let abstract know that there are no more
				return false;
			}
		}
		//countrymatchersarray is not false so it didn't return
		//as it is not false it may contain an array
		//however if pointer in array reached end
		if (false === list($key, $element) = each($this->_langISOMatchersArray)) {
			//fetch elements from db
			$this->_fillLMArray();
			//if there is nothing more in database
			if (false === $this->_langISOMatchersArray) {
				//let abstract know that there are no more
				return false;
			}
			//if reached here, there is something in array
			//and the pointer is different than null
			list($key, $element) = each($this->_langISOMatchersArray);
		}
		//reformat db row to abstract understandable
		return $this->reformatRow($element);
	}

	/**
	 * Will put an array or false in $_langISOMatchersArray
	 * 
	 * @return unknown_type
	 */
	private function _fillLMArray()
	{
		//adapt query to mode
		switch  (self::$_mode) {
			case self::MODE_FETCH_BLOCKS;
					$this->_langISOMatchersArray = $this->_Db->getBlock($this->_fetchCount);
				break;
			case self::MODE_FETCH_ALL;
					$this->_langISOMatchersArray = $this->_Db->getAll();
				break;
			case self::MODE_FETCH_ONE;
					$this->_langISOMatchersArray = $this->_Db->getOne($this->_fetchCount);
				break;
			default;
					throw new G_Lang_ISO_Normalizer_Adapter_Exception('Error : You must also put case in switch for _getSql() when adding a new mode or use the generic mode MODE_FETCH_ALL if you don\'t need to edit the sql.');
				break;
		}
		if ($this->_langISOMatchersArray !== false){//there are results from query
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
		$return = array();
		$return[$rowArray['langISO']] = array('regex' => $rowArray['regex'], 
											  'id'    => $rowArray['langISOId']);
		return $return;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see International/Country/Normalizer/Adapter/G_Country_Normalizer_Adapter_Abstract#reset()
	 */
	public function reset()
	{
		$this->_langISOMatchersArray = array();
		$this->_fetchCount = 0;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see International/LangISO/Normalizer/Adapter/G_Lang_ISO_Normalizer_Adapter_Abstract#getLangISOs()
	 */
	public function getLangISOs()
	{
		if (false === $langISOsArray = $this->_Db->getLangISOs()) {
			throw new G_Lang_ISO_Normalizer_Adapter_Exception('Error : Db does not have any langISOs');
		}
		return $langISOsArray;
	}

	/**
	 * 
	 * @param $lang
	 * @return unknown_type
	 */
	public function getCountriesWhereLangISOIsSpoken($lang)
	{
		$res = $this->_Db->getCountriesWhereLangISOIsSpoken($lang);
		//if there are no results
		if (false === $res) {
			//ensure the country is normalized|supported
			if (false === $this->_Db->isSupportedLangISO($lang)) {
				throw new G_Lang_ISO_Normalizer_Adapter_Exception('Error : the passed langISO str appears not to be normalized given :' . print_r($countryStr, true));
			} else {//no country speaks this language wierd
				throw new G_Lang_ISO_Normalizer_Adapter_Exception('The lang appears not to be spoken in any country ' . print_r($countryStr, true));
			}
		}
		return $res; //array('usa', 'england') || false
	}

	/**
	 * 
	 * @param $dirtyCountryStr
	 * @return unknown_type
	 */
	public function isNormalizedLangISOStr($lang)
	{
		return $this->_Db->isSupportedLangISO($lang);
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