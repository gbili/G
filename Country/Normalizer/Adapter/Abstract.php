<?php
/**
 * This class is meant to be the blueprint of the
 * normalizer adapters
 * 
 * Adatpters may use different ways to implements
 * these functions, except for the guessLangISO which
 * is adapter independent, that is why it is implemented
 * here.
 * 
 * @author gui
 *
 */
abstract class G_Country_Normalizer_Adapter_Abstract
{
	/**
	 * 
	 * @var unknown_type
	 */
	private $_countryId = null;
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct(){}
	
	/**
	 * Returns the next matcher
	 * @important beware of pointer wierd behaviour
	 * when mixing this function with other functions
	 * that use this function
	 * @return array | false
	 */
	abstract public function getNext();
	
	/**
	 * Reset the underlying matchers dataset
	 * (call whenever a new countryname
	 * is checked)
	 * 
	 * @return unknown_type
	 */
	abstract public function reset();
	
	/**
	 * Get the supported countries array
	 * 
	 * @return unknown_type
	 */
	abstract public function getCountries();
	
	/**
	 * Return true if the country name is the same as one of the
	 * normalized country name
	 * 
	 * @return unknown_type
	 */
	abstract public function isNormalizedCountryStr($dirtyCountryStr);
	
	/**
	 * This should throw an exception when the country
	 * passed cant  be mapped to a lang, with message:
	 * "You must call guessLangISO when country is not
	 *  Normalized. If you passed a normalized name then
	 *  check for the normalizedName -> lang iso mapping
	 *  in your adapter."
	 * 
	 * @param $normalizedCountryStr
	 * @return array | exception
	 */
	abstract public function getLangISOFromNormalizedCountry($normalizedCountryStr);
	
	/**
	 * Return a guess of the country name in english that the
	 * dirtyCountryStr should be mapped to ex:
	 * Espa�a -> Spain
	 * the lang spoken in that country
	 * ex : array(name=>'Spain', 'langISO'=>'es')
	 * false on fail
	 * 
	 * @param unknown_type $dirtyCountryStr
	 * @param unknown_type $returnDefaultOnFail
	 * @return array | false
	 */
	public function guessNormalizedInfo($dirtyCountryStr)
	{
		$dirtyCountryStr = G_Encoding::utf8Encode((string) $dirtyCountryStr);
		do {
			$countryMatcher = $this->getNext();
			//if there are no more matchers 
			//!IMPORTANT CHECK. without it: infinite loop!
			if (!$countryMatcher) {
				//reset the base dataset to make subsequent
				//calls of this function check against all matchers
				$this->reset();
				G_Echo::l1("\ndid not match any country normalizer, saving dirtyCountry id \n");
				$this->_countryId = $this->saveDirtyCountryStr($dirtyCountryStr);
				return false;
			}
			$countryName = key($countryMatcher);
		} while (!preg_match($countryMatcher[$countryName]['regex'], $dirtyCountryStr, $matches));
		//some normalizers may provide the country id
		if (isset($countryMatcher[$countryName]['id'])) {
			$this->_countryId = $countryMatcher[$countryName]['id'];
		}
		//preg match has found something
		//reset the base dataset to make subsequent
		//calls of this function check against all matchers
		$this->reset();
		return array('name'    => $countryName,
					 'langISO' => $countryMatcher[$countryName]['langISO']);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasCountryId()
	{
		return (null !== $this->_countryId);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getCountryId()
	{
		if (null === $this->_countryId) {
			throw new G_Lang_ISO_Normalizer_Adapter_Exception('The normalizer adapter does not provide any id for country, call hasCountryId() to avoid exception');
		}
		return $this->_countryId;
	}
}