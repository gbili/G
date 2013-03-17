<?php
class G_Participant_Savable_Db_Req
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
	 * @return unknown_type
	 */
	public function save(G_Participant_Savable $p)
	{
		$idOrFalse = $this->existsParticipant($p, true);
		if (false === $idOrFalse) {
			$this->insertUpdateData("INSERT INTO VideoEntity_SharedInfo_Participant (vESharedInfoId, mIEId, mIERoleId) VALUES (?, ?, ?)",
								array($p->getSharedInfo()->getId(),
									  $p->getMIE()->getId(),
									  $p->getRole()->getId()));
			$idOrFalse = $this->getAdapter()->lastInsertId();
		}
		$p->setId($idOrFalse);
	}
	
	/**
	 * 
	 * @param unknown_type $title
	 * @param unknown_type $returnIdIfAvailable
	 * @return boolean | integer
	 */
	public function existsParticipant(G_Participant_Savable $p, $returnIdIfAvailable = false)
	{
		return $this->existsElement("SELECT m.participantId AS id FROM VideoEntity_SharedInfo_Participant AS m WHERE m.vESharedInfoId = ? AND m.mIEId = ? AND m.mIERoleId = ?",
									array($p->getSharedInfo()->getId(),
										  $p->getMIE()->getId(),
										  $p->getRole()->getId()),
									'id',
									(boolean) $returnIdIfAvailable);
	}
	
	public function delete(G_Participant_Savable $p)
	{
		
	}
}