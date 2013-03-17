<?php
/**
 * This class is meant to load the Callback or Method class
 * given a path and a path type
 * 
 * @author gui
 *
 */
class G_Miner_Engine_BluePrint_Action_CMLoader
{
	/**
	 * cannot be 0
	 * @var unknown_type
	 */
	const CLASS_TYPE_CALLBACK = 12;
	const CLASS_TYPE_METHOD = 13;
	
	const PATH_TYPE_BASE = 21;
	const PATH_TYPE_DIRECT = 22;
	
	const ERROR_FILE_NOT_FOUND = 34;
	const ERROR_CLASS_NOT_FOUND = 35;
	
	/**
	 * 
	 * @var unknown_type
	 */
	static private $_loadedClasses = array();
	
	/**
	 * 
	 * @var unknown_type
	 */
	static private $_errors = array();
	
	/**
	 * 
	 * @param unknown_type $path
	 * @param G_Url_Authority_Host $host
	 * @param unknown_type $pathType
	 * @return unknown_type
	 */
	static public function loadCallbackClass($path, G_Url_Authority_Host $host, $pathType = self::PATH_TYPE_BASE)
	{
		return self::loadCMClass($path, $host, $pathType, self::CLASS_TYPE_CALLBACK);
	}
	
	/**
	 * 
	 * @param unknown_type $path
	 * @param G_Url_Authority_Host $host
	 * @param unknown_type $pathType
	 * @return unknown_type
	 */
	static public function loadMethodClass($path, G_Url_Authority_Host $host, $pathType = self::PATH_TYPE_BASE)
	{
		return self::loadCMClass($path, $host, $pathType, self::CLASS_TYPE_METHOD);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	static public function getErrors()
	{
		return self::$_errors;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	static public function methodExists($classNameOrObject, $methodName)
	{
		if (is_object($classNameOrObject)) {
			$classNameOrObject = get_class($classNameOrObject);
		}
		if (!in_array($classNameOrObject, self::$_loadedClasses)) {
			throw new G_Miner_Engine_BluePrint_Action_CMLoader_Exception('the class is not loaded yet, load the class and then make sure the method exists');
		}
		return method_exists($classNameOrObject, $methodName);
	}
	
	/**
	 * 
	 * @param unknown_type $path
	 * @param G_Url_Authority_Host $host
	 * @param unknown_type $pathType
	 * @param unknown_type $classType
	 * @return unknown_type
	 */
	static public function loadCMClass($path, G_Url_Authority_Host $host, $pathType = self::PATH_TYPE_BASE, $classType = self::CLASS_TYPE_CALLBACK)
	{
		if (!is_string($path)) {
			throw new G_Miner_Engine_BluePrint_Action_CMLoader_Exception('the path must be a string');
		}
		if ($pathType !== self::PATH_TYPE_BASE && $pathType !== self::PATH_TYPE_DIRECT) {
			throw new G_Miner_Engine_BluePrint_Action_CMLoader_Exception('the path type must be : G_Miner_Engine_BluePrint_Action_CMLoader::PATH_TYPE_DIRECT or G_Miner_Engine_BluePrint_Action_CMLoader::PATH_TYPE_BASE');
		}
		if (DIRECTORY_SEPARATOR !== mb_substr($path, -1)) {
			$path .= DIRECTORY_SEPARATOR;
		}
		
		$classTypeName = ($classType === self::CLASS_TYPE_CALLBACK)? 'Callback' : 'Method';
		
		//generate file path
		if ($pathType === self::PATH_TYPE_BASE) {
			$path .= $classTypeName . DIRECTORY_SEPARATOR;
		}
		$sl = str_replace(' ', '', ucwords(str_replace('-', ' ', strtolower($host->getSLDomain()))));
		$tl = ucfirst(strtolower($host->getTLDomain()));
		$fileName = $sl . $tl;
		//(when BASE : path/to/base/Method|Callback/HostCom.php) || (when DIRECT : path/to/direct/HostCom.php)
		$filePath = $path . $fileName . '.php';
		
		//make sure it exists
		if (!file_exists($filePath)) {
			self::$_errors[self::ERROR_FILE_NOT_FOUND] = 'The method/callback class file is not accessible or does not exist, given : ' . $filePath;
			return self::ERROR_FILE_NOT_FOUND;
		}
		require_once $filePath;
		//(when BASE : Method|Callback_HostCom) || (when DIRECT : Method|CallabckHostCom)
		$className = $classTypeName . (($pathType === self::PATH_TYPE_BASE)? '_' : '') . $fileName;
		if (!class_exists($className)) {
			self::$_errors[self::ERROR_CLASS_NOT_FOUND] = 'The class with name ' .$className . " does not exist in $filePath";
			return self::ERROR_CLASS_NOT_FOUND;
		}
		//add the loaded class name to stack
		self::$_loadedClasses[] = $className;
		return $className;
	}
}