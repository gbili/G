<?php
/**
 * Helps in reusing resources
 * 
 * @author gui
 *
 */
class G_Registry
{
	/**
	 * Contains keyed resources
	 * 
	 * @var unknown_type
	 */
	static private $_registry = array();
	
	/**
	 * 
	 * @return unknown_type
	 */
	static private $_allowOverwrite = false;
	
	/**
	 * forbid construction
	 * 
	 * @return unknown_type
	 */
	private function __construct()
	{}
	
	/**
	 * 
	 * @param unknown_type $key
	 * @param unknown_type $value
	 * @return unknown_type
	 */
	static public function register($key, $value)
	{
		$key = self::_validateKey($key);
		if (false === self::$_allowOverwrite && 
			isset(self::$_registry[$key])) {
			throw new G_Exception('G_Registry::_allowOverwrite is set to false and you are trying to register a value with the same key twice. key : ' . $key);
		}
		self::$_registry[$key] = $value;
	}
	
	/**
	 * 
	 * @param unknown_type $key
	 * @param unknown_type $value
	 * @return unknown_type
	 */
	static public function set($key, $value)
	{
		self::register($key, $value);
	}
	
	/**
	 * 
	 * @param unknown_type $key
	 * @return unknown_type
	 */
	static public function get($key)
	{
		$key = self::_validateKey($key);
		if (!isset(self::$_registry[$key])) {
			throw new G_Exception('You are trying to get a value with key that has not been set in regsitry, key : ' . $key);
		}
		return self::$_registry[$key];
	}
	
	/**
	 *  
	 * @param unknown_type $key
	 * @return unknown_type
	 */
	static public function isSetKey($key)
	{
		return isset(self::$_registry[self::_validateKey($key)]);
	}
	
	/**
	 * Returns the key if the value is in
	 * registry, or false if it is not
	 * 
	 * @param unknown_type $value
	 * @return unknown_type
	 */
	static public function getKey($value)
	{
		return array_search($value, self::$_registry, true);
	}
	
	/**
	 * Make sure key is ok
	 * 
	 * @param unknown_type $key
	 * @return unknown_type
	 */
	static private function _validateKey($key)
	{
		if (is_object($key)) {
			$key = get_class($key);
		}
		return $key;
	}
	
	/**
	 * 
	 * @param unknown_type $boolean
	 * @return unknown_type
	 */
	static public function allowOverwrite($boolean)
	{
		self::$_allowOverwrite = (boolean) $boolean;
	}
}