<?php
/**
 * 
 * @author gui
 *
 */
class G_Source_Savable_Db_Req
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
	public function save(G_Source_Savable $s)
	{
		if (!$this->hasTableName()) {
			throw new G_Db_Req_Exception('the table name must be set');
		}
		$idOrFalse = $this->existsSource($s, true);
		//only insert if does not already exist
		if (false === $idOrFalse) {
			$sql = "INSERT INTO {$this->getTableName()} (hostId, path) VALUES (:hostId, :path)";
			$this->insertUpdateData($sql, array(':hostId' => $s->getHost()->getId(),
												':path'  => (string) $s->getPath()));
			$idOrFalse = $this->getAdapter()->lastInsertId();
		}
		//$idOrFalse is an id
		$s->setId($idOrFalse);
	}
	
	/**
	 * 
	 * @param unknown_type $title
	 * @param unknown_type $returnIdIfAvailable
	 * @return boolean | integer
	 */
	public function existsSource(G_Source_Savable $source, $returnIdIfAvailable = false)
	{
		return $this->existsElement("SELECT t.sourceId AS id FROM {$this->getTableName()} AS t WHERE t.path = :path AND t.hostId = :hostId",
									array(':path' => (string) $source->getPath(),
										  ':hostId' => $source->getHost()->getId()),
									'id',
									(boolean) $returnIdIfAvailable);
	}
	
	public function delete()
	{
		
	}
}