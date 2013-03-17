<?php
class G_Video_Entity_SharedInfo_Savable_Db_Req
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
	 * (non-PHPdoc)
	 * @see Db/Buttons/G_Db_Buttons_Interface#save()
	 */
	public function save(G_Video_Entity_SharedInfo_Savable $vESI)
	{
		//don't check if it exists when it has no original title
		if ($idOrFalse = $vESI->hasOriginalTitle()) {
			$idOrFalse = $this->existsSharedInfo($vESI, true);
		}
		if (false === $idOrFalse) {
			$this->insertUpdateData("INSERT INTO VideoEntity_SharedInfo (date, countryId, timeLengthHHMMSS, originalTitleId) VALUES (:date, :countryId, :time, :originalTitleId)",
						array(':date'			 => $vESI->getDate(),
							  ':countryId' 		 => (($vESI->hasCountry())?$vESI->getCountry()->getId() : 0),
							  ':time' 			 => (($vESI->hasTimeLength())?$vESI->getTimeLength() : 0),
							  ':originalTitleId' => ($vESI->hasOriginalTitle())?$vESI->getOriginalTitle()->getId() : 0));
			$idOrFalse = $this->getAdapter()->lastInsertId();
			if ($vESI->hasGenre()) {
				foreach ($vESI->getGenres() as $g) {
					$this->insertUpdateData("INSERT INTO VideoEntity_SharedInfo_r_Genre (vESharedInfoId, genreId) VALUES (:vS, :g)",
											array(':vS' => $idOrFalse,//is id
												  ':g'	=> $g->getId()));
				}
			}
		}
		//$idOrFalse is an id
		$vESI->setId($idOrFalse);
		
		$this->associateImage($vESI);
	}
	
	/**
	 * 
	 * @param G_Video_Entity_SharedInfo_Savable $vESI
	 * @return unknown_type
	 */
	public function associateImage(G_Video_Entity_SharedInfo_Savable $vESI)
	{
		if (!$vESI->hasImage()) {
			return;
		}
		//see if there is something in db already
		$recordsetOrFalse = $this->getResultSet("SELECT si.isRecycled AS isRecycled,
														 si.imageId AS imageId
													FROM VideoEntity_SharedInfo_r_Image AS si 
													WHERE vESharedInfoId = :vESIId",
												 array(':vESIId' => $vESI->getId()));
		//if the image on db is recycled and the current is not change it
		if (false !== $recordsetOrFalse) {
			$row = current($recordsetOrFalse);
			if (!$vESI->isUsingRecycledImage() && 0 === (integer) $row['isRecycled']) {
				$this->insertUpdateData("DELETE FROM VideoEntity_SharedInfo_r_Image WHERE vESharedInfoId = :id", array(':id' => $vESI->getId()));
				//delete old image
				$i = new G_Image_Savable();
				$i->setId($row['imageId']);
				$i->delete();
			}
		}
												 
		//only save image if there is no image for this vESI
		if (false === $recordsetOrFalse) {
			$this->insertUpdateData("INSERT INTO VideoEntity_SharedInfo_r_Image (vESharedInfoId, imageId, isRecycled) VALUES (:vESIId, :imageId, :isRecycled)",
									array(':vESIId' => $vESI->getId(),
										  ':imageId' => $vESI->getImage()->getId(),
										  ':isRecycled' => $vESI->isUsingRecycledImage()));
		
		}
	}
	
	/**
	 * 
	 * @param unknown_type $title
	 * @param unknown_type $returnIdIfAvailable
	 * @return boolean | integer
	 */
	public function existsSharedInfo(G_Video_Entity_SharedInfo_Savable $vESI, $returnIdIfAvailable = false)
	{
		return $this->existsElement("SELECT si.vESharedInfoId AS id
										FROM VideoEntity_SharedInfo AS si
										WHERE si.originalTitleId = :originalTitleId 
											AND (si.date = :date 
												OR si.countryId = :countryId 
												OR si.timeLengthHHMMSS = :time)",
									array(':date'			 => $vESI->getDate(),
										  ':countryId' 		 => (($vESI->hasCountry())?$vESI->getCountry()->getId() : 0),
										  ':time' 			 => (($vESI->hasTimeLength())?$vESI->getTimeLength() : 0),
										  ':originalTitleId' => $vESI->getOriginalTitle()->getId()),
									'id',
									(boolean) $returnIdIfAvailable);
	}
	
	public function delete(G_Video_Entity_SharedInfo_Savable $vESI)
	{
		
	}
}