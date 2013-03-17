<?php
/**
 * 
 * @author gui
 *
 */
class G_Value_Savable_Db_Req
extends G_Db_Req_Abstract
{	
	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 
	 * @return void
	 */
	public function save(G_Value_Savable $v)
	{
		if (!$this->hasTableName()) {
			throw new G_Db_Req_Exception('the table name must be set');
		}
		$idOrFalse = $this->existsV($v->getValue(), true);
		//only insert if does not already exist
		if (false === $idOrFalse) {
			$sql = "INSERT INTO {$this->getTableName()} (value) VALUES (:value)";
			$this->insertUpdateData($sql, array(':value' => (string) $vS->getValue()));
			$idOrFalse = $this->getAdapter()->lastInsertId();
		}
		//$idOrFalse is an id
		$vS->setId($idOrFalse);
	}
	
	/**
	 * 
	 * @param unknown_type $title
	 * @param unknown_type $returnIdIfAvailable
	 * @return boolean | integer
	 */
	public function existsV($value, $returnIdIfAvailable = false)
	{
		return $this->existsElement("SELECT t.elementId AS id FROM {$this->getTableName()} AS t WHERE t.value = :value",
									array(':value' => (string) $value),
									'id',
									(boolean) $returnIdIfAvailable);
	}
	
	public function delete()
	{
		
	}
}