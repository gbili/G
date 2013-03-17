<?php
/**
 * Contains data that identifies a
 * video. Let's say all metadata
 * 
 * @todo On lang : if lang is not normalized and $_saveUnnormalizedLangsForPostTreatment
 * is true then save the unormalized lang to Db and associate it to the video entity
 * ensure the unormalized lang is not repeated in Db by doing a select before saving
 * then, all unormalized langs can be normalized manually...
 * 
 * @author gui
 *
 */
class G_Video_Entity_Savable
extends G_Molecule_Savable
{

	/**
	 * This is the language that the dumped site is in
	 * this is useful to give a language to all synopsis
	 * It allows video entities to have synopsis
	 * in many different languages. When dumping from
	 * different sites
	 * 
	 * @var 
	 */
	static private $_dumpedSiteDefaultLang;
	
	/**
	 * If no lang is found during the dumping process,
	 * this lang will be used instead
	 * 
	 * @param $l
	 * @return void
	 */
	static public function setDumpedSiteDefaultLang(G_Lang_Savable $l)
	{
		if (false === $l->hasISO()) {
			throw new G_Video_Entity_Exception('the G_Lang_Savable passed as dumpedSiteDefaultLang must have an iso, given : ' . $l->getValue());
		}
		self::$_dumpedSiteDefaultLang = $l;
	}
	
	/**
	 * @return G_Lang_Savable
	 */
	static public function getDumpedSiteDefaultLang()
	{
		if (null === self::$_dumpedSiteDefaultLang) {
			throw new G_Video_Entity_Exception('the dumpedSiteDefaultLang must be set'); 
		}
		return self::$_dumpedSiteDefaultLang;
	}
	
	/**
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 
	 * @param unknown_type $title
	 * @return void
	 */
	public function setTitle(G_Video_Entity_Title_Savable $title)
	{
		$this->_setElement('title', $title);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasTitle()
	{
		return $this->isSetKey('title');
	}
	
	/**
	 * 
	 * @param unknown_type $title
	 * @return unknown_type
	 */
	public function getTitle()
	{
		return $this->getElement('title');
	}
	
	/**
	 * Even if not valid the lang is stored here, and then the requestor
	 * will need to decide whether to save de langDirty or not depending
	 * on G_International_LangISO::$saveUnnormalizedLangsForPostTreatment
	 * 
	 * @param G_International_LangISO $lang
	 * @return unknown_type
	 */
	public function setLang(G_Lang_Savable $lang)
	{
		$this->_setElement('lang', $lang);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getLang()
	{
		if ( ! $this->isSetKey('lang')) {
			//use default lang when it was not set in dumping process
			$this->setLang(self::getDumpedSiteDefaultLang());
		}
		return $this->getElement('lang');
	}
	
	/**
	 * 
	 * @param unknown_type $d
	 * @return unknown_type
	 */
	public function setSynopsis($d)
	{
		$this->_setElement('synopsis', (string) $d);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getSynopsis()
	{
		return $this->getElement('synopsis');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasSynopsis()
	{
		return $this->isSetKey('synopsis');
	}

	/**
	 * 
	 * @return unknown_type
	 */
	public function hasSharedInfo()
	{
		return $this->isSetKey('sharedInfo');
	}

	/**
	 * Automatically creates the shared info object
	 * if not already set
	 * 
	 * @return unknown_type
	 */
	public function getSharedInfo()
	{
		if (false === $this->isSetKey('sharedInfo')) {
			$this->setSharedInfo(new G_Video_Entity_SharedInfo_Savable());
		}
		return $this->getElement('sharedInfo');
	}

	/**
	 * 
	 * @param G_Video_Entity_SharedInfo $shI
	 * @return unknown_type
	 */
	public function setSharedInfo(G_Video_Entity_SharedInfo_Savable $shI)
	{
		$this->_setElement('sharedInfo', $shI);
	}

	/**
	 * Overwrites existing sources
	 * 
	 * @param array $sources
	 * @return unknown_type
	 */
	public function setSources(array $array)
	{
		foreach ($array as $source) {
			if (!($source instanceof G_Source)) {
				throw new G_Video_Entity_Savable_Exception('You are trying to add at least a source which is not one');
			}
		}
		//don't overwrite the existing sources origin, just put them as outdatedAtom
		//and add this ones to the current and use this origin
		$this->_useKeyAsArrayAndPushValue('sources', $array, false, false);
	}

	/**
	 * 
	 * @param G_Source $source
	 * @return unknown_type
	 */
	public function addSource(G_Source_Savable $source)
	{
		$this->_useKeyAsArrayAndPushValue('sources', $source, G_Molecule_Savable::POST_SAVE_LOOP);
	}

	/**
	 * 
	 * @param array $array
	 * @return unknown_type
	 */
	public function addSources(array $array)
	{
		foreach ($array as $source) {
			if (!($source instanceof G_Source_Savable)) {
				throw new G_Video_Entity_Savable_Exception('You are trying to add at least a source which is not one');
			}
		}
		//overwrite existing sources array origin, with the one at the time
		//of this function call, and push the sources to the existing array
		$this->_useKeyAsArrayAndPushValue('sources', $source, G_Molecule_Savable::POST_SAVE_LOOP);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getSources()
	{
		return $this->getElement('sources');
	}
}