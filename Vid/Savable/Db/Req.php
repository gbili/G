<?php
/**
 * 
 * @author gui
 *
 */
class G_Vid_Savable_Db_Req
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
	public function save(G_Vid_Savable $v)
	{
		if (!$this->hasTableName()) {
			throw new G_Db_Req_Exception('the table name must be set');
		}
		$idOrFalse = $this->existsVid($v, true);
		//only insert if does not already exist
		if (false === $idOrFalse) {
			$sql = "INSERT INTO {$this->getTableName()} (titleId, sourceId, imageId, categoryId, date, timeLength) VALUES (:titleId, :sourceId, :imageId, :categoryId, :date, :timeLength)";
			$this->insertUpdateData($sql, 
									array(':titleId' => $v->getTitle()->getId(),
										 ':sourceId' => $v->getSource()->getId(),
										 ':imageId' => $v->getImage()->getId(),
										 ':categoryId' => $v->getCategory()->getId(),
										 ':date' => $v->getDate()->getUnixTimeStamp(),
										 ':timeLength' => $v->getTimeLength()));
			$idOrFalse = $this->getAdapter()->lastInsertId();
			//$this->_saveVidToTagRelation($idOrFalse, $v->getTags());
		}
		//$idOrFalse is an id
		$v->setId($idOrFalse);
	}
	
	/**
	 * 
	 * @param $vidId
	 * @param $tags
	 * @return unknown_type
	 */
	private function _saveVidToTagRelation($vidId, array $tags)
	{
		$values = array();
		$qMrks = '';
		foreach ($tags as $tag) {
			$values[] = $vidId;
			$values[] = $tag->getId();
			$qMrks .= '(?,?),';
		}
		if (mb_strlen($qMrks) > 0) {
			$qMrks = mb_substr($qMrks, 0, -1);
			$this->insertUpdateData("INSERT INTO G_Vid_r_Tag (vidId, tagId) VALUES ($qMrks)", $values);
		}
	}
	
	/**
	 * 
	 * @param unknown_type $title
	 * @param unknown_type $returnIdIfAvailable
	 * @return boolean | integer
	 */
	public function existsVid(G_Vid_Savable $v, $returnIdIfAvailable = false)
	{
		return $this->existsElement("SELECT t.vidId AS id FROM {$this->getTableName()} AS t WHERE t.sourceId = :sourceId",
									array(':sourceId' => $v->getSource()->getId()),
									'id',
									(boolean) $returnIdIfAvailable);
	}
	
	public function delete()
	{
		
	}
}