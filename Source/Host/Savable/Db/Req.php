<?php
/**
 * 
 * @author gui
 *
 */
class G_Source_Host_Savable_Db_Req
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
	public function save(G_Source_Host_Savable $h)
	{
		if (!$this->hasTableName()) {
			throw new G_Db_Req_Exception('the table name must be set');
		}
		$idOrFalse = $this->existsSourceHost($h, true);
		//only insert if does not already exist
		if (false === $idOrFalse) {
			$sql = "INSERT INTO {$this->getTableName()} (host, hFName) VALUES (:host, :hFName)";
			$this->insertUpdateData($sql, array(':host' => $h->getHost()->toString(),
												':hFName'  => $h->getHFName()));
			$idOrFalse = $this->getAdapter()->lastInsertId();
		}
		//$idOrFalse is an id
		$h->setId($idOrFalse);
	}
	
	/**
	 * 
	 * @param unknown_type $title
	 * @param unknown_type $returnIdIfAvailable
	 * @return boolean | integer
	 */
	public function existsSourceHost(G_Source_Host_Savable $host, $returnIdIfAvailable = false)
	{
		return $this->existsElement("SELECT t.hostId AS id FROM {$this->getTableName()} AS t WHERE t.host = :host",
									array(':host' => (string) $host->getHost()->toString()),
									'id',
									(boolean) $returnIdIfAvailable);
	}
	
	public function delete()
	{
		
	}
}