<?php
/**
 * 
 * @author gui
 *
 */
class G_Video_Entity_Savable_Db_Req
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
	 * LangISO and LangDirty have no overlapping ids
	 * so they can be saved as langId and there will
	 * be no confusion because langISOId range (1-146)
	 * and langDirtyId range (147 - infinte) 
	 * 
	 * @return unknown_type
	 */
	public function save(G_Video_Entity_Savable $vE)
	{
		$idOrFalse = $this->existsVideoEntity($vE, true);
		if (false === $idOrFalse) {
			$this->insertUpdateData("INSERT INTO VideoEntity (vETitleId, vESharedInfoId, langId) VALUES (:vETitleId, :vESharedInfoId, :langId) ",
								array(':vETitleId' => $vE->getTitle()->getId(),
									  ':vESharedInfoId' => $vE->getSharedInfo()->getId(),
									  ':langId' => (($vE->getLang()->hasISO())? $vE->getLang()->getISO()->getId() : $vE->getLang()->getId()))); //use default value for iso if not available
			//from here idOrFalse = id
			$idOrFalse = $this->getAdapter()->lastInsertId();
			//save description if available
			if ($vE->hasSynopsis()) {
				$this->_saveSynopsis($idOrFalse, $vE->getSynopsis());
			}
		}
		$vE->setId($idOrFalse);
	}
	
	/**
	 * 
	 * @param unknown_type $vEId
	 * @param unknown_type $synopsis
	 * @return unknown_type
	 */
	private function _saveSynopsis($vEId, $synopsis)
	{
		if (!$this->existsSynopsis($vEId)) {
			$this->insertUpdateData("INSERT INTO VideoEntity_Synopsis (videoEntityId, synopsis) VALUES (:vEId, :synopsis)",
									array(':vEId' => (integer) $vEId,
										  ':synopsis' => (string) $synopsis));
		}
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function existsSynopsis($vEId)
	{
		return $this->existsElement("SELECT v.videoEntityId AS id FROM VideoEntity_Synopsis AS v WHERE v.videoEntityId = :vEId",
									array(':vEId' => $vEId),
									'id',
									false);
	}
	
	/**
	 * 
	 * @param unknown_type $title
	 * @param unknown_type $returnIdIfAvailable
	 * @return boolean | integer
	 */
	public function existsVideoEntity(G_Video_Entity_Savable $vE, $returnIdIfAvailable = false)
	{
		$varToValuesArray = array();
		$sql = "SELECT ve.videoEntityId AS id 
				FROM VideoEntity AS ve
				  WHERE ve.vETitleId = :vETitleId 
					AND ve.vESharedInfoId = :vESharedInfoId 
					AND ve.langId = :langId";
		$varToValuesArray[':vETitleId'] = $vE->getTitle()->getId();
		$varToValuesArray[':vESharedInfoId'] = $vE->getSharedInfo()->getId();
		$varToValuesArray[':langId'] = ($vE->getLang()->hasISO())? $vE->getLang()->getISO()->getId() : $vE->getLang()->getId();
		return $this->existsElement($sql,
									$varToValuesArray,
									'id',
									(boolean) $returnIdIfAvailable);
	}

	public function delete(G_MIE_Savable $mIE)
	{
		
	}
}