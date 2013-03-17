<?php
/**
 * Avoid having lots of Db instances
 * Singletton
 * 
 * 
 * 
 * @author gui
 *
 */
class G_Db_Registry
{
	/**
	 * If you want to use another
	 * adapter you have to change
	 * this name to what your classes end with
	 * 
	 * Every class will be appended this name
	 * if the second parameter in getInstance is true
	 * 
	 * @var unknown_type
	 */
	static private $_classNameEndPart = '_Db_Req';
	
	/**
	 * 
	 * @var unknown_type
	 */
	static private $_instances;
	
	/**
	 * Make it a static class
	 * 
	 * @return unknown_type
	 */
	private function __construct(){}
	
	/**
	 * Get the instance of a given class name
	 * 
	 * @param string | object $className
	 * @return unknown_type
	 */
	static public function getInstance($className)
	{
		//allow the user to pass an instance intead of the class name
		if (is_object($className)) {
			$className = get_class($className);
		}	
		//append the end of full class name
		$className .= self::$_classNameEndPart;
		if (!isset(self::$_instances[$className])) {
			self::$_instances[$className] = new $className();
		}
		return self::$_instances[$className];
	}
	
	/**
	 * Add an instance to the registry that needs
	 * parameters to be passed to the constructor
	 * 
	 * @param G_Db_Req_Abstract $instance
	 * @return unknown_type
	 */
	static public function setInstance($instance)
	{
		self::$_instances[get_class($instance)] = $instance;
	}
	
	/**
	 * 
	 * @param unknown_type $key
	 * @param unknown_type $requestorInstance
	 * @return unknown_type
	 */
	static public function registerInstance($key, G_Db_Req_Abstract $requestorInstance)
	{
		$className = get_class($requestorInstance);
		//only allow one instance per class
		if (isset(self::$_instances[$className])) {
			//drop $requestorInstance from param and get the instance from self::$_instances[$className]
			$requestorInstance = self::$_instances[$className];
		}
		if (is_object($key)) {
			$key = get_class($key);
		}
		if (!is_string($key)) {
			throw new G_Db_Registry_Exception('You must pass either an object or a string for param $key');
		}
		//now when calling getInstance() it will return $requestorInstance 
		self::$_instances[$key . self::$_classNameEndPart] = $requestorInstance;
	}
	
	/**
	 * This will get the param $end
	 * and prepend an underscore
	 * 
	 * @param unknown_type $end
	 * @return unknown_type
	 */
	static public function setClassNameEndPart($end)
	{
		if (!is_string($end))  {
			throw new G_Db_Exception('Error : the setClassNameEndPart() parameter must be a string');
		}
		self::$_classNameEndPart = '_Db_' . $end;
	}
	
	/**
	 * Get the class names that have currently been registered
	 * 
	 * @return unknown_type
	 */
	static public function getRegisteredClassNames()
	{
		return array_keys(self::$_instances);
	}
	
	/**
	 * get an array with classname and isntance
	 * 
	 * @return unknown_type
	 */
	static public function getRegisteredInstances()
	{
		return self::$_instances;
	}
}