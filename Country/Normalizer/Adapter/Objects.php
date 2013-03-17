<?php
/**
 * In this approach, the normalized countries name is
 * the same as the name of the files that contain the classes
 * Each FileSystem_Item_... class has a match() method
 * that will return :
 * 	-(true or countryName) when the dirtyCountryStr represents its country
 *  -false when not match
 * 
 * Normalized means that there is a file with the countryName.php in .../FileSystem/Item 
 * @todo all this shit has to be rethought
 * @author gui
 *
 */
class G_Country_Normalizer_Adapter_Objects
extends G_Country_Normalizer_Adapter_Abstract
{
	/**
	 * 
	 * @var unknown_type
	 */
	const BASE_NORMALIZER_CLASS_NAME = 'G_Country_Normalizer_Adapter_Objects_';
	
	/**
	 * Contains an array with
	 * suported countries when
	 * getCountries() has been called
	 * 
	 * @var array
	 */
	protected $_countriesNames;
	
	/**
	 * Contains an iterator for the
	 * country matchers directory
	 * 
	 * @var unknown_type
	 */
	private $_directoryIterator;
	
	
	/**
	 * Creates a directory iterator
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		parent::__construct();
		//make the matchers available
		//to the instance with a
		//directory iterator
		$validatorsDir = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Objects';
		$this->_directoryIterator = new DirectoryIterator($validatorsDir);
	}
	
	/**
	 * Walks the Item directory
	 * to find country names
	 * @note Create a new instance if you
	 * want the method to recheck
	 * the base data set for new
	 * countries names
	 * 
	 * @return unknown_type
	 */
	public function getCountries()
	{
		//do not regenerate the list if already available
		if (!empty($this->_countriesNames)) {
			return $this->_countriesNames;
		}
		//reset the directory iterator to
		//ensure the check is made against all country names
		//note: dont fear conflicts with getNext() from within
		//the abstract class because parent::guessNormalizedInfo()
		//doesnt mix $this->getNext() and isNormalizedCountryStr()
		//beware of this when calling getNext() directly from outside
		$this->reset();
		$funcRes = $this->_getNextCountryName();//returns false or a country name
		while (false !== $funcRes){
			$this->_countriesNames[] = $funcRes;
			//continue to loop till end
			$funcRes = $this->_getNextCountryName();//returns false or a country name
		}
		return $this->_countriesNames;
	}
	
	/**
	 * This function will return the current element
	 * and advance pointer of $_directoryIterator
	 * 
	 * @return unknown_type
	 */
	protected function _getNextCountryName()
	{
		// get the current element and advance pointer
		do {
			if (false === $this->_directoryIterator->valid()) {
				return false; //if reached end of iterator
			}
			$element = $this->_directoryIterator->current();
			//move the pointer forward
			$this->_directoryIterator->next();
		//skip these files
		} while ($element->isDir() || $element->getFileName() === 'Abstract.php');
		return mb_substr($element->getFileName(), 0, -4);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see International/Country/Normalizer/Adapter/G_Country_Normalizer_Adapter_Abstract#getNext()
	 */
	public function getNext()
	{
		/*
		 * create an the final array with $element contents
		 */
		$countryName = $this->_getNextCountryName();//this will advance pointers
		//create the normalizer object
		$className = self::BASE_NORMALIZER_CLASS_NAME . $countryName;//"_" is included in BNCN
		$matcher = new $className();
		if (! ($matcher instanceof G_Country_Normalizer_Adapter_Objects_Abstract)) {
			throw new G_International_Exception('Error : The country normalizer does not extend G_Country_Normalizer_Adapter_FileSystem_Item_Abstract given : ', print_r($normalizer, true));
		}
		//return an array understandable by the abstract class
		return array($countryName => array('regex'  =>$matcher->getRegex(),
										 'langISO'=>$matcher->getLangISO()));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see International/Country/Normalizer/Adapter/G_Country_Normalizer_Adapter_Abstract#isNormalizedCountryStr($dirtyCountryStr)
	 */
	public function isNormalizedCountryStr($countryStr)
	{
		//reset the directory iterator to
		//ensure the check is made against all country names
		//note: dont fear conflicts with getNext() from within
		//the abstract class because parent::guessNormalizedInfo()
		//doesnt mix $this->getNext() and isNormalizedCountryStr()
		//beware of this when calling getNext() directly from outside
		$this->reset();
		$funcRes = $this->_getNextCountryName();//returns false or a country name
		while (false !== $funcRes){
			//see if the country str is the same as
			//one of the country names
			if ($funcRes === $countryStr) {
				return true;
			}
			//not the same, renew func res and loop
			$funcRes = $this->_getNextCountryName();//returns false or a country name
		}
		return false;
	}

	/**
	 * (non-PHPdoc)
	 * @see International/Country/Normalizer/Adapter/G_Country_Normalizer_Adapter_Abstract#reset()
	 */
	public function reset()
	{
		$this->_directoryIterator->rewind();
	}

	/**
	 * If you pass a non normalized country name
	 * it will retrun false
	 *  
	 * @param $normalizedCountryStr
	 * @return false | string | array of strings
	 */
	public function getLangISOFromNormalizedCountry($normalizedCountryStr)
	{
		//if not normalized return
		if (!$this->isNormalizedCountryStr($normalizedCountryStr)) {
			return false;
		}
		$normalizerName = BASE_NORMALIZER_CLASS_NAME . $normalizedCountryStr();
		$normalizer = new $normalizerName();
		return $normalizer->getLangISO();
	}
}