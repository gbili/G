<?php
/**
 * This class is used as a placeholder
 * for the Db adapter plus it forces
 * the adapter to have an interface.
 * 
 * @author gui
 *
 */
abstract class G_Db_Req_Abstract
implements G_Db_Interface
{
	/**
	 * The name of the prefixed adapter
	 * that should be used when no prefiexed
	 * adapter matches the prefix passed
	 * as argument
	 * 
	 * @var unknown_type
	 */
	const FALLBACK_ADAPTER_PREFIX = 'fallbackPrefix';
	
	/**
	 * Contains the adapter
	 * @var unknown_type
	 */
	static private $_adapter = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_instanceAdapterPointer = null;
	
	/**
	 * Allow to specify a table name
	 * 
	 * @var unknown_type
	 */
	private $_tableName = null;
	
	/**
	 * Forces class to have adapter
	 * before instanciation
	 * 
	 * @return unknown_type
	 */
	public function __construct($useDifferentPrefixedAdapter = null)
	{
		if (null === self::$_adapter) {
			throw new G_Db_Exception('You must set a Db adapter before instanciating any G_Db_Req_Abstract sublcass.');
		}
		if (null !== $useDifferentPrefixedAdapter) {
			$this->_instanceAdapterPointer = self::getPrefixedAdapter($useDifferentPrefixedAdapter);
		}
	}
	
	/**
	 * 
	 * @param unknown_type $name
	 * @return unknown_type
	 */
	public function setTableName($name)
	{
		$this->_tableName = $name;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getTableName()
	{
		if (false === $this->hasTableName()) {
			throw new G_Db_Req_Exception("Table name is not set cannot getTableName() untill it is set by setTableName()");
		}
		return $this->_tableName;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasTableName()
	{
		return null !== $this->_tableName;
	}
	
	/**
	 * @todo ensure adapter is ok
	 * @param G_Db_Adapter_Interface $adapter
	 * @return unknown_type
	 */
	static public function setAdapter($adapter)
	{
		self::$_adapter = $adapter;
	}
	
	/**
	 * Prefix must be of zend class syntax
	 * if there was an not prefixed adapter
	 * it will be lost
	 * 
	 * @param unknown_type $adapter
	 * @param unknown_type $prefix
	 * @return unknown_type
	 */
	static public function setPrefixedAdapter($adapter, $prefix)
	{
		if (!is_array(self::$_adapter)) {
			$oldAdapter = self::$_adapter;
			self::$_adapter = array();
			if (null !== $oldAdapter) {//keep a trace of old adapter
				self::$_adapter[self::FALLBACK_ADAPTER_PREFIX] = $oldAdapter;
			}
		}
		self::$_adapter[$prefix] = $adapter;
	}
	
	/**
	 * Try to find the closest adapter
	 * 
	 * 
	 * @param unknown_type $prefix
	 * @return unknown_type
	 */
	static public function getPrefixedAdapter($prefix)
	{
		if (isset(self::$_adapter[$prefix])) {
			return self::$_adapter[$prefix];
		}

		$closestAdapter = null;
		$buildingPref = '';
		$firstTime = true;
		foreach (explode('_', $prefix) as $part) {
			if (true === $firstTime) {
				$buildingPref .= $part;
				$firstTime = false;
			} else {
				$buildingPref .= '_' . $part;
			}
			if (isset(self::$_adapter[$buildingPref])) {
				$closestPrefix = $buildingPref;
				$closestAdapter = self::$_adapter[$buildingPref];
			}
		}
		//no adapter found
		if (null === $closestAdapter) {
			if (!isset(self::$_adapter[self::FALLBACK_ADAPTER_PREFIX])) {
				throw new G_Db_Exception('There is no adapter for this prefix, neither a fallback adapter. Requested Prefix : ' . $prefix . ', available Prefixes : ' . print_r(self::$_adapter, true));
			}
			//try to get fallback adapter
			$closestAdapter = self::$_adapter[self::FALLBACK_ADAPTER_PREFIX];
		}
		
		return $closestAdapter;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	static public function getPrefixedAdapters()
	{
		if (!is_array(self::$_adapter)) {
			throw new G_Db_Req_Exception('There are no prefixed adapters');
		}
		return self::$_adapter;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Db/G_Db_Interface#setAdapter($adapter)
	 */
	public function setInstanceAdapter($adapter)
	{
		$this->_instanceAdapterPointer = $adapter;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getAdapter()
	{
		if (null === $this->_instanceAdapterPointer) {
			if (is_array(self::$_adapter)) {
				$this->_instanceAdapterPointer = self::getPrefixedAdapter(get_class($this));
			} else {
				return self::$_adapter;
			}
		}
		return $this->_instanceAdapterPointer;
	}
	
	/**
	 * 
	 * @param unknown_type $adapterPrefix
	 * @return unknown_type
	 */
	public function setDifferentPrefixedAdapter($adapterPrefix)
	{
		$this->setInstanceAdapter(self::getPrefixedAdapter($adapterPrefix));
	}
	
	/**
	 * Creates a PDOStatement with the given params
	 * throws up on errors
	 * 
	 * @param unknown_type $sql
	 * @param array $values
	 * @param unknown_type $fetchMode
	 * @return PDOStatement
	 */
	private function _prepareAndExecuteStatement($sql, array $values = array())
	{
		if (!is_string($sql)) {
			throw new G_Db_Req_Exception('You passed a non string argument given : ' . print_r($sql));
		}
		$pdoStmt = $this->getAdapter()->prepare($sql);
		if (false === $pdoStmt) {
			throw new G_Db_Req_Exception('The database could not successfully prepare the PDOStatement : ' . print_r($this->getAdapter()->errorInfo(), true));
		}
		//bind values if provided
		$res = (!empty($values))? $pdoStmt->execute($values) : $pdoStmt->execute();
		if (false === $res) {
			throw new G_Db_Req_Exception('The PDOStatement did not execute() successfully : ' . print_r($pdoStmt->errorInfo(), true) . ' SQL : ' . $sql . ' values : ' . print_r($values, true));
		}
		return $pdoStmt;
	}
	
	/**
	 * If errors throws up
	 * otherwise if empty resultset return false
	 * otherwise returns array resultset
	 * 
	 * @param string $sql SQL statement
	 * @param array $values an array mapping each variable key in $sql to a value 
	 * @return false | array
	 */
	public function getResultSet($sql, array $values = array(), $fetchMode = PDO::FETCH_ASSOC)
	{
		$pdoStmt = $this->_prepareAndExecuteStatement($sql, $values);
		if ($pdoStmt->rowCount() === 0) {
			return false;
		}
		return $pdoStmt->fetchAll($fetchMode);
	}
	
	/**
	 * Inserts the data and returns the lasInsertId
	 * @param unknown_type $sql
	 * @param array $values
	 * @return PDOStatement
	 */
	public function insertUpdateData($sql, array $values = array())
	{
		$pdoStmt = $this->_prepareAndExecuteStatement($sql, $values);
		//always close cursor to allow other queries
		$pdoStmt->closeCursor();
		return true;
	}
	
	/**
	 * If the user wants to get the id if the elment exists, then
	 * he must specify the $idColumnName and set $returnId = true
	 * 
	 * @param $sql
	 * @param $values
	 * @param $idColumnName
	 * @param $returnId
	 * @return unknown_type
	 */
	public function existsElement($sql, array $values, $idColumnName = null, $returnId = false)
	{
		$res = $this->getResultSet($sql, $values);
		if (false === $returnId) {
			//the user wants only a boolean result
			return (boolean) $res;
		}
		//arrived here then the user wants to get the id or false
		if (null === $idColumnName) {
			throw new G_Db_Req_Exception('You must specify the $idColumnName parameter, if you want to be able to get the id in existsElement()');
		}
		//return the id if the res is an array or return false
		return (is_array($res))? $res[0][(string) $idColumnName] : false;
	}

}