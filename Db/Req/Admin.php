<?php
/**
 * Functions that help administrate the database
 * but that are not usefull for the application
 * they are placed here to lighten the normal
 * requestor
 * 
 * @author gui
 *
 */
class G_Db_Req_Admin
extends G_Db_Req_Abstract
{
	
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
	 * 
	 * @param unknown_type $char
	 * @param unknown_type $len
	 * @return unknown_type
	 */
	static public function genStr($char, $len)
	{
		$str = '';
		for ($i=0; $i < $len; $i++) {
			$str .= $char;
		}
		return $str;
	}

	/**
	 * 
	 * @param unknown_type $len
	 * @param unknown_type $string
	 * @param unknown_type $char
	 * @return unknown_type
	 */
	static public function fillEmptySpace($len, $string, $char = ' ')
	{
		$strLen = mb_strlen($string);
		if ($strLen + 2 > $len) {
			throw new G_Db_Exception('the string length must be greater than the len param');
		}
		$string = ' ' . htmlentities($string) . self::genStr($char, ($len - ($strLen + 1)));
		return $string;
	}

	/**
	 * 
	 * @param unknown_type $separator
	 * @param array $items
	 * @param array $itemsMaxLenCount
	 * @param unknown_type $fillEmtpySpaceWith
	 * @return unknown_type
	 */
	static public function genLine($separator, array $items, array $itemsMaxLenCount, $fillEmtpySpaceWith = '&nbsp;')
	{
		$finalStr = (string) $separator;
		foreach ($items as $key => $value) {
			$finalStr .= self::fillEmptySpace($itemsMaxLenCount[$key] + 2, $value, $fillEmtpySpaceWith) . $separator;
		}
		return $finalStr;
	}

	/**
	 * 
	 * @param unknown_type $title
	 * @param unknown_type $rows recordsetArray
	 * @return unknown_type
	 */
	static public function renderRecordSetAsTable($title, array $rows)
	{
		$strLens = array();
		//find out what are the longest value strings for each field
		foreach ($rows as $row) {
			foreach ($row as $key => $val) {
				if (!isset($strLens[$key])) {
					$strLens[$key] = mb_strlen($key);
				}
				if ($strLens[$key] < ($len = mb_strlen($val))) {
					$strLens[$key] = $len; //add room for two empty spaces
				}
			}
		}
		//create strings of '-' with length of strLens + 2
		$strLensLine = array();
		foreach ($strLens as $key => $len) {
			$strLensLine[$key] = self::genStr('-', $len + 2);
		}
		//glue all strings to make the top and bottom line
		$topBottomLine = implode('+', $strLensLine);
		$topBottomLine = '+' . $topBottomLine . '+';
		
		//now generate table line by line
		$finalStr = '<ul style="width:' . mb_strlen($topBottomLine) . 'em"><h4>' . (string) $title . "</h4>\n";
		// +-------------+-------------+--------------+
		$finalStr .= '<li>' . $topBottomLine . '</li>' . "\n";
		// | Field1      | Field2      | Field3       |
		$finalStr .= '<li>' . self::genLine('|', array_combine(array_keys($rows[0]), array_keys($rows[0])), $strLens) . '</li>' . "\n";
		// +-------------+-------------+--------------+
		$finalStr .= '<li>' . $topBottomLine . '</li>' . "\n";
		// | Value1      | Value2      | Value3       |
		foreach ($rows as $row) {
			$finalStr .= '<li>' . self::genLine('|', $row, $strLens) . '</li>' . "\n";
		}
		// +-------------+-------------+--------------+
		$finalStr .= '<li>' . $topBottomLine . '</li>'. "\n";
		$finalStr .= '</ul>' . "\n";
		
		return $finalStr;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getTablesList()
	{
		$list = array();
		$tables = $this->getResultSet('SHOW TABLES', array(), PDO::FETCH_NUM);
		if (!empty($tables)) {
			foreach ($tables as $row) {
				$list[] = $row[0];
			}
		}
		return $list;
	}
	
	/**
	 * 
	 * @param $tableName
	 * @return unknown_type
	 */
	public function getTableFieldsList($tableName, $skipAutoIncr = false)
	{
		$list = array();
		if (false === $this->tableExists($tableName)) {
			throw new G_Db_Req_Exception('the table does not exist');
		}
		
		if (false === $r = $this->describeTable($tableName)) {
			throw new G_Db_Req_Exception('there are no fileds in table');
		}
		
		if (true === $skipAutoIncr) {
			foreach ($r as $row) {//only make checks inside loop if skip is true
				if ('auto_increment' === $row['Extra']) {
					continue;//dont add this row to $list
				}
				$list[] = $row['Field'];
			}
		} else {
			foreach ($r as $row) {
				$list[] = $row['Field'];
			}
		}
		return $list;
	}
	
	/**
	 * 
	 * @param $tableName
	 * @return unknown_type
	 */
	public function describeTable($tableName)
	{
		return $this->getResultSet("DESCRIBE $tableName");
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getDatabaseName()
	{
		if (false === $res = $this->getResultSet("SELECT database() AS db")) {
			throw new G_Db_Req_Exception('There was a ne error while retrieving the database name');
		}
		list($k, $v) = each($res);
		return $v['db'];
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function describeDatabase()
	{
		$tablesStr = '<ul style="margin-left:-95%">';
		$count = 0;
		foreach ($this->getResultSet('SHOW TABLES', array(), PDO::FETCH_NUM) as $row) {
			$count++;
			$tablesStr .= '<li style="padding-bottom:1em">';
			$tablesStr .= self::renderRecordSetAsTable($count . '. ' . $row[0], $this->getResultSet('DESCRIBE ' . $row[0], array(), PDO::FETCH_ASSOC));
			$tablesStr .= '</li>';
		}
		$tablesStr .= '</ul>';
		return '<h4>Tables List (' . $count . ' tables) : </h4>' . $tablesStr;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function showTablesContent()
	{
		$tablesStr = '<ul style="margin-left:-95%">';
		$count = 0;
		foreach ($this->getTablesList() as $name) {
			$count++;
			if ($resultSet = $this->getResultSet('SELECT * FROM ' . $name)) {
				$tablesStr .= '<li style="padding-bottom:1em">';
				$tablesStr .= self::renderRecordSetAsTable($count . '. ' . $name, array_slice($resultSet, 0, 50));
				$tablesStr .= '</li>';
			}
		}
		$tablesStr .= '</ul>';
		return '<h4>Tables List (' . $count . ' tables) : </h4>' . $tablesStr;
	}
	
	/**
	 * 
	 * @param unknown_type $tableName
	 * @return unknown_type
	 */
	public function showTableContent($tableName)
	{
		if (false !== $res = $this->getResultSet('SELECT * FROM ' . $tableName, array(), PDO::FETCH_ASSOC)) {
			$res = self::renderRecordSetAsTable($tableName, $res);
		}
		return $res;
	}
	
	/**
	 * If no parameter provided = ALL tables
	 * 
	 * @param array $tables
	 * @return unknown_type
	 */
	public function deleteTables($tables = array())
	{	
		if (!is_array($tables)) {
			$tables = array($tables);
		}
		if (empty($tables)) {
			$tables = $this->getTablesList();
		}
		foreach ($tables as $tableName) {
			$this->insertUpdateData('DROP TABLE IF EXISTS ' . $tableName);
		}
		//make sure all tables were deleted
		$notDeletedTables = $this->getTablesList();
		$failedDeletes = array_intersect($tables, $notDeletedTables);
		if (!empty($failedDeletes)) {
			throw new G_Db_Req_Exception('The tables: ' . implode(', ', $failedDeletes) . ' were not deleted successfully');
		}
	}
	
	/**
	 * 
	 * @param unknown_type $tables
	 * @return unknown_type
	 */
	public function emptyTables($tables = array())
	{
		if (!is_array($tables)) {
			$tables = array($tables);
		}
		if (empty($tables)) {
			$tables = $this->getTablesList();
		}
		foreach ($tables as $table) {
			if (!$this->tableExists($table)) {
				throw new G_Db_Req_Exception('There is no table with name : ' . $tableName . ', in db :' . $this->getDatabaseName());
			}
			//delete all table rows
			$this->insertUpdateData("DELETE FROM $table WHERE 1=1");
		}
	}
	
	/**
	 * This takes a database tables definitions
	 * and updates the table definition of the
	 * table in database.
	 * 
	 * 
	 * 
	 * @param $tableName
	 * @param $pathToTableDefinitionFile
	 * @return unknown_type
	 */
	public function changeTableScheme($tableName, $pathToTableDefinitionFile)
	{
		//get table definition from database file definitions
		$defStr = file_get_contents($pathToTableDefinitionFile);
		$r = new G_Regex($defStr, new G_Regex_String_Generic('CREATE TABLE `' . $tableName . '`[^;]+;'));
		if (!$r->match()) {
			throw new G_Db_Req_Exception('There is no scheme definition for table with name : ' . $tableName . ', in file : ' . $pathToTableDefinitionFile);
		}
		$newTableDefinition = 'DROP TABLE IF EXISTS `' . $tableName . '`;' . $r->getMatches(0);
		
		//create swap table definition
		$swapTableName = $tableName . '_GSRSwap';
		$swapTableDefStr = preg_replace("#`($tableName)`#uis", $swapTableName, $newTableDefinition);
		//create swap table
		$this->insertUpdateData($swapTableDefStr);
		//insert into swap
		$this->transfuseContent($tableName, $swapTableName);
		
		//create new table definition, delete old definition
		$this->deleteTables(array($tableName));
		$this->insertUpdateData($newTableDefinition);
		//swap back
		$this->transfuseContent($swapTableName, $tableName);
		//delete swap
		$this->deleteTables(array($swapTableName));
	}
	
	/**
	 * 
	 * @param unknown_type $tableName
	 * @return unknown_type
	 */
	public function tableExists($tableName)
	{
		return in_array($tableName, $this->getTablesList());
	}
	
	/**
	 * 
	 * @param unknown_type $set1
	 * @param unknown_type $set2
	 * @return unknown_type
	 */
	public function getSubset($set1, $set2)
	{
		//if the values present in both are the same that the ones in $set1 it means $set2 has all values of $set1 so $set2 is a superset of $s1
		$a = array_intersect($set1, $set2);
		if (empty($a)) {
			throw new G_Db_Req_Exception('There is no intersection between the two sets, source : ' . print_r($set1, true) . ' destination : ' . print_r($set2, true));
		}
		return $a;
	}
	
	/**
	 * Allows to transfuse content froma table
	 * with more and less columns than the
	 * destination
	 * 
	 * @param unknown_type $sourceTName
	 * @param unknown_type $destTName
	 * @return unknown_type
	 */
	public function transfuseContent($sourceTName, $destTName)
	{
		//the qurey needs to have the same set of fields on insert and on select
		//if there are more fields in one table than the other, we only need those
		//that are present in both
		$sqlFields = implode(', ', $this->getSubset($this->getTableFieldsList($sourceTName), $this->getTableFieldsList($destTName)));
		//get all contents of source and insert them int dest
		return $this->insertUpdateData("INSERT INTO $destTName ($sqlFields) (SELECT $sqlFields FROM $sourceTName)");
	}
}