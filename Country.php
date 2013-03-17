<?php
/**
 * Because you cannot know whether the string passed as a country name
 * is meaningfull to the application, each time a string is passed
 * on construction, the constructor will get a Normalizer instance
 * and try to retrieve meaningfull countries. If it can't do it,
 * $_isNormalizerd will be false.
 * 
 * Given a string this class will return a country object
 * that matches the string
 * If no country matches the string then 
 * 
 * @author gui
 *
 */
class G_Country
{
	private $_id = null;
	/**
	 * The language in which this country uses
	 * to record its movies
	 * 
	 * @var unknown_type
	 */
	private $_langs = array();
	
	/**
	 * The country name normalized if possible
	 * 
	 * @var unknown_type
	 */
	private $_name;
	
	/**
	 * If normalization failed (ie: it couldn't
	 * find a suitable name) then this will
	 * be false 
	 * @var unknown_type
	 */
	private $_isNormalized;
	
	/**
	 * Forces country name and lang consistency
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $lang
	 * @return void
	 */
	public function __construct($countryStr, array $spokenLang = array())
	{
		//ensure the data is not meaningless
		$normalizer = G_Country_Normalizer::getInstance();
		//get a coherent name
		$cInfo = $normalizer->guessNormalizedInfo($countryStr);
		//if it succeed then $cInfo will be an array (false otherwise)
		$this->_isNormalized = is_array($cInfo);
		//this will propulate the country
		//with meaningfull data or with 
		//the parameters data
		if ($this->_isNormalized) {
			if (!isset($cInfo['name'])) {
				throw new G_Country_Exception('Normalization did not return standard array');
			}
			$countryStr = $cInfo['name'];//sanitized country str
			if (!isset($cInfo['langISO']) || !is_array($cInfo['langISO'])) {
				throw new G_Country_Exception('Not standard normalization, langs must be set as an array of strings given : ' . print_r($spokenLang, true));
			}
			$spokenLang = $cInfo['langISO'];
			foreach ($spokenLang as $lang) {
				$this->_langs[] = new G_Lang_Savable($lang);
			}
		}
		if ($normalizer->hasCountryId()) {
			$this->_id = $normalizer->getCountryId();
		}

		//it may be normalized or not
		$this->_name = $countryStr;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * 
	 * @return unknown_type
	 */
	public function getLangs()
	{
		return $this->_langs;
	}
	
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasId()
	{
		return (null !== $this->_id);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getId()
	{
		if (null === $this->_id) {
			throw new G_Country_Exception('The normalizer did not provide any id');
		}
		return $this->_id;
	}
	
	/**
	 * If normalization failed 
	 * @return unknown_type
	 */
	public function isNormalized()
	{
		return $this->_isNormalized;
	}
}