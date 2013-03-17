<?php
/**
 * This class allows you to use all the
 * functionallities of G_Db_Req_Abstract
 * and to sort of use a singleton
 * 
 * @author gui
 *
 */
class G_Db_Req
extends G_Db_Req_Abstract
{
	/**
	 * Allow one instance to be registered at
	 * class level
	 * 
	 * @var unknown_type
	 */
	static private $_registeredInstance = null;
	
	/**
	 * 
	 * @param unknown_type $differentPrefixedAdapter
	 * @return unknown_type
	 */
	public function __construct($differentPrefixedAdapter = null)
	{
		parent::__construct($differentPrefixedAdapter);
	}
	
	/**
	 * Register the current instance
	 * in class
	 * It will be returned by getRegisteredInstance()
	 * When using this feature, understand that the 
	 * registered instance can be accessed by anyone
	 * having access to the class, and sometimes this
	 * is ¡not safe!
	 * 
	 * @return unknown_type
	 */
	public function register()
	{
		if (null === self::$_registeredInstance) {
			self::$_registeredInstance = $this;
		} else {
			if (self::$_registeredInstance !== $this) {
				throw new G_Db_Req_Exception('You class registry is already filled with another instance, you must call unregister from that instance before being able to register again');
			}
		}
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function unregister()
	{
		//ignore unregister when null
		if (null !== self::$_registeredInstance) {
			if (self::$_registeredInstance !== $this) {
				throw new G_Db_Req_Exception('This instance is not allowed to free the class regsitry, you must unregister from the same instance that registered itself');
			}
			unset(self::$_registeredInstance);
		}
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function isEmptyRegistry()
	{
		return null === self::$_registeredInstance;
	}
	
	/**
	 * Return the registered instance
	 * 
	 * @return unknown_type
	 */
	static public function getRegInstance()
	{
		if (null === self::$_registeredInstance) {
			self::$_registeredInstance = new self();//will instantiate on falback prefix
		}
		return self::$_registeredInstance;
	}
}