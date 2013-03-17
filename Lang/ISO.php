<?php
/**
 * This class is used By G_Lang_Savable to validate
 * the input. You can pass a dirty lang and it will
 * get the iso equivalent when possible (the lang passed is supported)
 * To validate the lang it uses the normalizer which uses one of
 * the adapters available, ie the storage adapter which has been
 * implemented.
 * Every lang iso has an id
 * 
 * @author gui
 *
 */
class G_Lang_ISO
{	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_id = null;
	
	/**
	 * The lang iso
	 * @var unknown_type
	 */
	private $_langISO;
	
	/**
	 * Contains the lang before normalization
	 * 
	 * @var unknown_type
	 */
	private $_dirtyLangStr;
	
	/**
	 * 
	 * @param unknown_type $langIso
	 * @return unknown_type
	 */
	public function __construct($dirtyLangStr)
	{
		$this->_dirtyLangStr = (string) $dirtyLangStr;
		$normalizer = G_Lang_ISO_Normalizer::getInstance();
		if (is_numeric($id = $normalizer->isNormalizedLangISOStr($dirtyLangStr))) {
			$this->_id = $id;
			$this->_langISO = $dirtyLangStr;
			$this->_isValid = true;
		} else {
			$langISO = $normalizer->guessNormalizedInfo($this->_dirtyLangStr);
			if ($this->_isValid = (boolean) $langISO) {
				$this->_langISO = $langISO;
				if ($normalizer->hasLangISOId()) {
					$this->_id = $normalizer->getLangISOId();
				}
			}
		}
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getDirtyLang()
	{
		return $this->_dirtyLangStr;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getValue()
	{
		if (false === $this->_isValid) {
			throw new G_Lang_ISO_Exception('The lang could not be validated');
		}
		return $this->_langISO;
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
			throw new G_Lang_ISO_Exception('The normalizer did not provide any id');
		}
		return $this->_id;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function isValid()
	{
		return $this->_isValid;
	}
}