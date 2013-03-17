<?php
/**
 * Allow easy retrieval of lexer instances.
 * 
 * There must only be one lexer per entity class 
 * meaning that if you are dumping videos, you 
 * will have created a video class, and there 
 * will be only one lexer instance for all the videos 
 * instances. If you are dumping appartements, 
 * hotel rooms, flights etc. there will be one 
 * lexer class for each of these and one lexer instance
 * per lexer class.
 * 
 * @see G_Miner_Engine_Lexer_Abstract
 * 
 * 
 * 
 * Singletton
 * 
 * @author gui
 *
 */
class G_Miner_Engine_Lexer_Registry
{
	/**
	 * If you want to use another
	 * adapter you have to change
	 * this name to what your classes end with
	 * 
	 * Every class will be appended this name
	 * 
	 * @var unknown_type
	 */
	static private $_classNameEndPart = '_Lexer';
	
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
	 * @param unknown_type $className
	 * @return unknown_type
	 */
	static public function getInstance($className)
	{
		if (is_object($className)) {
			$className = get_class($className);
		}
		//append the end of full class name
		$className .= self::$_classNameEndPart;
		if (!isset(self::$_instances[$className])) {
			if (!class_exists($className)) {
                require_once 'Zend/Loader.php';
                Zend_Loader::loadClass($className);
            }
			$instance = new $className();
			if (!($instance instanceof G_Miner_Engine_Lexer_Abstract)) {
				throw new G_Miner_Engine_Lexer_Exception($className . ' must be an instance of G_Miner_Engine_Lexer_Abstract');
			}
			self::$_instances[$className] = $instance;
		}
		return self::$_instances[$className];
	}
	
	/**
	 * Add an instance to the registry that needs
	 * parameters to be passed to the constructor
	 * 
	 * @param unknown_type $instance
	 * @return unknown_type
	 */
	static public function setInstance($instance)
	{
		self::$_instances[get_class($instance)] = $instance;
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
		self::$_classNameEndPart = $end;
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