<?php
/**
 * Get the parent class
 */
require_once 'Abstract.php';

/**
 * This class is meant to be used for Normalization
 * it extends Abstract and gets the data from an
 * array
 * 
 * @author gui
 *
 */
class G_Country_Normalizer_Adapter_Array 
extends G_Country_Normalizer_Adapter_Abstract
{
	/**
	 * Supported lang iso
	 * @todo add regex to each
	 * @var unknown_type
	 */
	private $_langsISOArray = array('or' => array('regex' => 'orisha'),
									'as', //...
									'aa',
									'ab',
									'af',
									'am',
									'ar',
									'ay',
									'az',
									'ba',
									'be',
									'bg',
									'bh',
									'bi',
									'bn',
									'bo',
									'br',
									'ca',
									'co',
									'cs',
									'cy',
									'da',
									'de',
									'dz',
									'el',
									'en',
									'eo',
									'es',
									'et',
									'eu',
									'fa',
									'fi',
									'fj',
									'fo',
									'fr',
									'fy',
									'ga',
									'gd', 
									'gl',
									'gn',
									'gu',
									'gv', 
									'ha',
									'he',
									'hi',
									'hr',
									'hu',
									'hy',
									'ia',
									'id',
									'ie',
									'ik',
									'is',
									'it',
									'iu',
									'ja',
									'jw',
									'ka',
									'kk',
									'kl',
									'km',
									'kn',
									'ko',
									'ks',
									'ku',
									'kw',
									'ky',
									'la',
									'lb',
									'ln',
									'lo',
									'lt',
									'lv',
									'mg',
									'mi',
									'mk',
									'ml',
									'mn',
									'mo',
									'mr',
									'ms',
									'mt',
									'my',
									'na',
									'ne',
									'nl',
									'no',
									'oc',
									'om',
									'pa',
									'pl',
									'ps',
									'pt',
									'qu',
									'rm', 
									'rn',
									'ro',
									'ru',
									'rw',
									'sa',
									'sd',
									'se', 
									'sg',
									'sh', 
									'si',
									'sk',
									'sl',
									'sm',
									'sn',
									'so',
									'sq',
									'sr',
									'ss',
									'st',
									'su',
									'sv',
									'sw',
									'ta',
									'te',
									'tg',
									'th',
									'ti',
									'tk',
									'tl',
									'tn',
									'to',
									'tr',
									'ts',
									'tt',
									'tw',
									'ug',
									'uk',
									'ur',
									'uz',
									'vi',
									'vo',
									'wo',
									'xh',
									'yi',
									'yo',
									'za',
									'zh',
									'zu');
	

	/**
	 * (non-PHPdoc)
	 * @see International/Country/Normalizer/Adapter/G_Country_Normalizer_Adapter_Abstract#getNext()
	 */
	public function getNext()
	{
		
		if (!(list($cName, $array) = each($this->_countryMatchersArray))) {
			return false;//end of array
		}
		return array($cName => $array);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see International/Country/Normalizer/Adapter/G_Country_Normalizer_Adapter_Abstract#reset()
	 */
	public function reset()
	{
		reset($this->_countryMatchersArray);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see International/Country/Normalizer/Adapter/G_Country_Normalizer_Adapter_Abstract#getCountries()
	 */
	public function getCountries()
	{
		return array_keys($this->_countryMatchersArray);
	}

	/**
	 * (non-PHPdoc)
	 * @see International/Country/Normalizer/Adapter/G_Country_Normalizer_Adapter_Abstract#isNormalizedCountryStr($dirtyCountryStr)
	 */
	public function isNormalizedCountryStr($dirtyCountryStr)
	{
		return array_key_exists($dirtyCountryStr, $this->_countryMatchersArray);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see International/Country/Normalizer/Adapter/G_Country_Normalizer_Adapter_Abstract#getLangISOFromNormalizedCountry($normalizedCountryStr)
	 */
	public function getLangISOFromNormalizedCountry($normalizedCountryStr)
	{
		if (!$this->isNormalizedCountryStr($normalizedCountryStr)) {
			return false;
		}
		return $this->_countryMatchersArray[$normalizedCountryStr]['langISO'];
	}

}