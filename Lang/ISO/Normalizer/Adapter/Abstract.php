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
abstract class G_Lang_ISO_Normalizer_Adapter_Abstract
{
	private $_langISOId = null;

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
	 * Get the supported lang iso array
	 * 
	 * @return unknown_type
	 */
	abstract public function getLangISOs();
	
	/**
	 * Return true if the country name is the same as one of the
	 * normalized country name
	 * 
	 * @return unknown_type
	 */
	abstract public function isNormalizedLangISOStr($dirtyLangStr);
	
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
	public function guessNormalizedInfo($dirtyLangStr)
	{
		$dirtyLangStr = G_Encoding::utf8Encode((string) $dirtyLangStr);
		do {
			$langISOMatcher = $this->getNext();
			//if there are no more matchers 
			//!IMPORTANT CHECK. without it: infinite loop!
			if (!$langISOMatcher) {
				//reset the base dataset to make subsequent
				//calls of this function check against all matchers
				$this->reset();
				return false;
			}
			$langISO = key($langISOMatcher);
		} while (!preg_match($langISOMatcher[$langISO]['regex'], $dirtyLangStr, $matches));
		//some normalizers may provide the lang iso id
		if (isset($langISOMatcher[$langISO]['id'])) {
			$this->_langISOId = $langISOMatcher[$langISO]['id'];
		}
		//preg match has found something
		//reset the base dataset to make subsequent
		//calls of this function check against all matchers
		$this->reset();
		return $langISO;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasLangISOId()
	{
		return (null !== $this->_langISOId);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getLangISOId()
	{
		if (null === $this->_langISOId) {
			throw new G_Lang_ISO_Normalizer_Adapter_Exception('The normalizer adapter does not provide any id for lang iso, call hasLangISOId() to avoid exception');
		}
		return $this->_langISOId;
	}
}