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
	 * 
	 * 
	 * @var unknown_type
	 */
	private $_countryMatchersArray = array(
					'Spain' => 
						array('regex'   => '/Espa\\p{L}\\p{M}*[ae]|Spa(?:nien|gna)|Spain/i',
							  'langISO' => array(G_International_LangISO::ES,
												G_International_LangISO::CA,
												G_International_LangISO::GL,
												G_International_LangISO::EU)),
					'Australia' => 
						array('regex'   => '/Austr\\p{L}\\p{M}*li(?:a|en)/i',
							  'langISO' => array(G_International_LangISO::EN)),
					'Portugal' => 
						array('regex'   => '/Portugal|Portogallo/i',
							  'langISO' => array(G_International_LangISO::PT)),
					'France' => 
						array('regex'   => '/Fran\\p{L}\\p{M}*(?:ia|[ea]|kreich)/i',
							  'langISO' => array(G_International_LangISO::FR)),
					'Usa' => 
						array('regex'   => '/u\\.?s\\.?a\\.?|e\\.?e\\.?u\\.?u\\.?|Vereinigte[- _.]Staa?ten|Estados[- _.]Un\\p{L}\\p{M}*dos.*?(?:Am\\p{L}\\p{M}*rica)?|United[- _.]states.*?(?:America)?|\\p{L}\\p{M}*tats[-_. ]unis[-_. ]d?.?Am\\p{L}\\p{M}*rique|Stati[ -_.]Uniti(?:[ -_.]d.america)?/i',
							  'langISO' => array(G_International_LangISO::EN)),
					'Canada' => 
						array('regex'   => '/[CK]anad\\p{L}\\p{M}*/i',
							  'langISO' => array(G_International_LangISO::EN,
												G_International_LangISO::FR)),
					'Austria' => 
						array('regex'   => '/\\p{L}\\p{M}*e?sterreich|Au(?:stria|triche)/i',
							  'langISO' => array(G_International_LangISO::DE)),
					'Switzerland' => 
						array('regex'   => '/Switzerland|Suisse|Suiza|Svizzera|Schweiz|Su\\p{L}\\p{M}*\\p{L}\\p{M}*a/i',
							  'langISO' => array(G_International_LangISO::FR,
												G_International_LangISO::DE,
												G_International_LangISO::IT)),
					'Belgium' => 
						array('regex'   => '/B\\p{L}\\p{M}*lgi(?:um|que|ca|en)/i',
							  'langISO' => array(G_International_LangISO::FR)),
					'Uk' => 
						array('regex'   => '/[UV]\\.?K\\.?|United Kingdom|Britain|Royaume-Uni|Re[ig]no Uni[dt]o|Vereinigtes K(?:\\p{L}\\p{M}*|oe)nigreich/i',
							  'langISO' => array(G_International_LangISO::EN)),
					'Italy' => 
						array('regex'   => '/(?:It\\p{L}\\p{M}*l[yi](?:a|(?:en))?)/i',
							  'langISO' => array(G_International_LangISO::IT)),
					'Germany' => 
						array('regex'   => '/German(?:y|ia)|Aleman[ih]a|Allemagne|Deutschland/i',
							  'langISO' => array(G_International_LangISO::DE)),
					'Argentina' => 
						array('regex'   => '/Argentin(?:[ae]|ien)/i',
							  'langISO' => array(G_International_LangISO::ES)),
					'Netherlands' => 
						array('regex'   => '/Pays-Bas|Pa\\p{L}\\p{M}*ses-Ba(?:j|ix)os|Paesi-Bassi|Niederlande|Netherlands/i',
							  'langISO' => array(G_International_LangISO::NL))
					);
	

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