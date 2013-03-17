<?php
/**
 * Lang_Savable is a placeholder for the dirtylang
 * from that dirtylang string, it will try to create
 * a G_Lang_ISO. If it succeeds then you can get the
 * G_Lang_ISO id withg $this->getISO()->getId()
 * @see G_Lang_ISO methods
 * @see G_Lang_Savable_Db_Req to see what happens when
 * there is no iso
 * 
 * @author gui
 *
 */
class G_Lang_Savable
extends G_Molecule_Savable
{
	/**
	 * When no G_Lang_ISO is found
	 * and this is true, G_Lang_ISO_Db_Req::save()
	 * will create an id for the dirtylang value
	 * 
	 * @var boolean
	 */
	static public $saveUnnormalizedLangsForPostTreatment = true;

	/**
	 * If a G_Lang_ISO was
	 * matched from the dirtystring,
	 * this will be true
	 * @var boolean
	 */
	private $_hasISO = false;	
	
	/**
	 * 
	 * @return void
	 */
	public function __construct($dirtyLangStr)
	{
		parent::__construct();
		$this->_setElement('langDirty', (string) $dirtyLangStr);
		//try to get the lang ISO from the dirty lang str
		$langISO = new G_Lang_ISO($dirtyLangStr);
		if ($this->_hasISO = $langISO->isValid()) {
			$this->_setElement('langISO', $langISO);
		}
	}
	
	/**
	 * Holds the string 
	 * passed to the constructor
	 * 
	 * @return string
	 */
	public function getDirtyValue()
	{
		return $this->getElement('langDirty');
	}
	
	/**
	 * proxy
	 * @return string
	 */
	public function getValue()
	{
		return $this->getDirtyValue();
	}
	
	/**
	 * 
	 * @return G_Lang_ISO
	 */
	public function getISO()
	{
		return $this->getElement('langISO');
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function hasISO()
	{
		return $this->_hasISO;
	}

}