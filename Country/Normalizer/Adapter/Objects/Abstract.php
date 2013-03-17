<?php
/**
 * These classes are meant to normalize a country
 * name. Espa�a -> Spain
 * Schweiz -> Switzerland ...
 * A set of classes, one per supported country will
 * recive a string and return the normalized version
 * if they know how to do it, otherwise they return
 * false.
 * Supported langs : 
 * ES,FR,PT,DE,IT,EN, (these langs correspond to lang
 * in which the countryStr may be passed to be recognized)
 * @author gui
 *
 */
abstract class G_Country_Normalizer_Adapter_Objects_Abstract
{
	/**
	 * contains the regex to match
	 * the country str
	 * 
	 * to be overriden
	 * @var unknown_type
	 */
	protected $_regex;
	/**
	 * contains the lang spoken
	 * in the country, if country
	 * speaks many use array.
	 * Use the langs from
	 * G_International_LangISO
	 * constants
	 * 
	 * 
	 * to be overriden
	 * @var unknown_type
	 */
	protected $_langISO;
	
	/**
	 * Tells if the country
	 * speaks many langs
	 * 
	 * @var unknown_type
	 */
	private $_multilang;
	
	/**
	 * Boostrap and force sbclasses
	 * to specify $_regex and $_langISO
	 * 
	 * @return unknown_type
	 */
	final public function __construct()
	{
		if (null === $this->_regex) {
			throw new G_International_County_Exception('You must specify the $_regex member string from the subclass.');
		}
		if (null === $this->_langISO) {
			throw new G_International_County_Exception('You must specify the $_langISO member string from the subclass.');
		}
		//if lang iso is an array then multilang is true
		$this->_multilang = is_array($this->_langISO);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	final public function getRegex()
	{
		return $this->_regex;
	}
	
	/**
	 * Corresponds to the lang spoken
	 * in the country
	 *  
	 * @return unknown_type
	 */
	final public function getLangISO()
	{
		return $this->_langISO;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	final public function isMultilang()
	{
		return $this->_multilang;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	final public function getCountryName()
	{
		$str = get_class($this); //G_Bla_Bla_France
		$array = explode('_', $str);//array(Dupmer,Bla,Bla,France)
		return array_pop($array); //France
	}
}