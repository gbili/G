<?php
/**
 * The normalizer is meant to return a Normalizer adapter
 * 
 * 
 * @author gui
 *
 */
class G_Lang_ISO_Normalizer
{	
	/**
	 * 
	 * @var unknown_type
	 */
	const UNKNOWN_LANG = 'Unknown';
	
	/**
	 * the base class name that will be prepended to
	 * default adapter
	 * 
	 * @var unknown_type
	 */
	static public $baseAdapterClassName = 'G_Lang_ISO_Normalizer_Adapter';
	
	/**
	 * This will be used when $adapterClassName
	 * is not specified
	 * 
	 * @var unknown_type
	 */
	static public $defaultAdapterName = 'Db';

	/**
	 * Determines which adapter to return in getInstance()
	 * @var unknown_type
	 */
	static public $adapterName;
	
	/**
	 * Contains the adapter instance
	 * 
	 * @var unknown_type
	 */
	static private $_adapterInstance;
	
	/**
	 * 
	 * @return unknown_type
	 */
	private function __construct(){}
	
	/**
	 * This will return the desired adapter instance
	 * specified in self::$adapterClassName
	 * 
	 * @return unknown_type
	 */
	static public function getInstance()
	{
		if (null === self::$_adapterInstance) {
			if (null === self::$adapterName) {
				self::$adapterName = self::$defaultAdapterName;
			}
			$className = self::$baseAdapterClassName . '_' . ucfirst(self::$adapterName);
			self::$_adapterInstance = new $className();
			if (!(self::$_adapterInstance instanceof G_Lang_ISO_Normalizer_Adapter_Abstract)) {
				throw new G_Lang_ISO_Normalizer_Adapter_Exception('Error : The adapter must extend G_Lang_ISO_Normalizer_Adapter_Abstract');
			}
		}
		return self::$_adapterInstance;
	}
	
	/**
	 * Force class to create a new instance
	 * when user calls getInstance().
	 * Use this when you update the adapterName
	 * 
	 * @return unknown_type
	 */
	static public function flushInstance()
	{
		self::$_adapterInstance = null;
	}
}