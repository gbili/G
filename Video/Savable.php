<?php
/**
 * 
 * @author gui
 *
 */
class G_Video_Savable
extends G_Molecule_Savable
{
	/**
	 * Contains temporary data stored
	 * as key=>values
	 * 
	 * @var array
	 */
	private $_buckets;
	
	//originalEntity G_Video_Entity_Savable
	//dubbedEntity array of G_Video_Entity_Savable
	//sharedInfo
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Ensure all entities get the _same_ shared info pointer
	 * 
	 * @param G_Video_Entity_Savable $entity
	 * @return unknown_type
	 */
	private function _negotiateSharedInfo(G_Video_Entity_Savable $entity)
	{
		//if this has shraed info
		if (true === $this->hasSharedInfo()) {
			//set entity shared info
			//but before see which one has the most attribs
			if ($entity->hasSharedInfo()) {
				if (!$this->getElement('sharedInfo')->hasCountry() &&
					$entity->getSharedInfo()->hasCountry()) {
					return $this->getElement('sharedInfo')->setCountry($entity->getSharedInfo()->getCountry());
				}
			}
			$entity->setSharedInfo($this->getElement('sharedInfo'));
		} else {
			//if entity has shared info
			if ($entity->hasSharedInfo()) {
				//set this shared inf
				$this->_setElement('sharedInfo', $entity->getSharedInfo());
			}
		}
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
	 * 
	 * @return unknown_type
	 */
	public function hasOriginalEntity()
	{
		return $this->isSetKey('originalEntity');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasDubbedEntities()
	{
		return $this->isSetKey('dubbedEntities');
	}
	
	/**
	 * This will overwrite existing data with the new
	 * 
	 * @param G_Video_Entity_Savable $entity
	 * @return unknown_type
	 */
	public function setOriginalEntity(G_Video_Entity_Savable $entity)
	{
		$this->_negotiateSharedInfo($entity);
		if ($this->isSetKey('originalEntity')) {
			throw new G_Video_Exception('The original entity can only be set once');
		}
		$this->_setElement('originalEntity');
	}

	/**
	 * Return the original entity
	 * 
	 * @return G_Video_Entity_Savable
	 */
	public function getOriginalEntity()
	{
		return $this->getElement('originalEntity');
	}
	
	/**
	 * 
	 * @param G_Video_Entity_Savable $entity
	 * @return unknown_type
	 */
	public function addDubbedEntity(G_Video_Entity_Savable $entity)
	{
		$this->_negotiateSharedInfo($entity);
		$this->_useKeyAsArrayAndPushValue('dubbedEntities', $entity, true, true);
	}
	
	/**
	 * Return the dubbed entities array
	 * @return array
	 */
	public function getDubbedEntities()
	{
		return $this->getElement('dubbedEntities');
	}
	
	/**
	 * Store temporary data (value) under key
	 * 
	 * @param unknown_type $key
	 * @param unknown_type $value
	 * @return unknown_type
	 */
	public function setBucket($key, $value)
	{
		$this->_buckets[$key] = $value;
	}
	
	/**
	 * Get the value sotred in $_buckets
	 * under the key pased as param
	 * 
	 * @param unknown_type $key
	 * @return unknown_type
	 */
	public function getBucket($key)
	{
		if (isset($this->_buckets[$key])) {
			throw new G_Video_Exception('Trying to access non existing bucket with key : ' . print_r($key, true));
		}
		return $this->_buckets[$key];
	}
}