<?php
/**
 * 
 * @author gui
 *
 */
class G_Image_Savable_Db_Req
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
	 * Make a first save to get an id
	 * and then if it already has an id make
	 * a second save to update the path
	 * 
	 * This is done because the local image name
	 * in file system is generated based on the
	 * id given by the database
	 * 
	 * @param $i
	 * @return unknown_type
	 */
	public function save(G_Image_Savable $i)
	{
		$idOrFalse = $this->existsImage($i->getFileName());
		if (false === $idOrFalse) {
			//save on file system before db
			$i->fileSystemSave();
			//save on db
			$this->insertUpdateData("INSERT INTO {$this->getTableName()} (localUrl) VALUES (:localUrl) ",
								array(':localUrl' => $i->getFileName())); //save with blank path to get an id
			$idOrFalse = $this->getAdapter()->lastInsertId();
		} else {
			G_Echo::l1("Image with local url : {$i->getFileName()} already exists in Db with id = $idOrFalse, so it was not fileSystemSave()d");
		}
		$i->setId($idOrFalse);
	}
	
	/**
	 * 
	 * @param unknown_type $localUrl
	 * @return unknown_type
	 */
	public function existsImage($localUrl)
	{
		return $this->existsElement("SELECT i.imageId AS id FROM {$this->getTableName()} AS i WHERE localUrl = :localUrl",
									array(':localUrl' => $localUrl),
									'id',
									true);
	}
	
	/**
	 * The problem with this function is that it does not
	 * abstract a shit
	 * 
	 * @param G_Image_Savable $i
	 * @return unknown_type
	 */
	public function fetch(G_Image_Savable $i)
	{
		//only fetch if data is missing
		if ($i->hasId() && $i->hasLocalUrl()) {
			return true;
		}
		if ($i->hasId()) {
			if (false !== ($recordSetOrFalse = $this->getResultSet("SELECT i.localUrl AS localUrl FROM {$this->getTableName()} AS i WHERE i.imageId = :imageId", array(':imageId' => $i->getId())))) {
				$row = current($recordSetOrFalse);
				$i->setLocalUrl($row['localUrl']);
			}
		} else if ($i->hasLocalUrl()) {
			if (false !== ($recordSetOrFalse = $this->getResultSet("SELECT i.imageId AS imageId FROM {$this->getTableName()} AS i WHERE i.localUrl = :localUrl", array(':localUrl' => $i->getLocalUrl())))) {
				$row = current($recordSetOrFalse);
				$i->setId($row['imageId']);
			}
		}
		return ($i->hasId() && $i->hasLocalUrl());
	}
	
	/**
	 * 
	 * @param G_Image_Savable $i
	 * @return unknown_type
	 */
	public function delete(G_Image_Savable $i)
	{
		if ($this->fetch($i)) {
			$this->insertUpdateData("DELETE FROM {$this->getTableName()} WHERE imageId = :id", array(':id' => $i->getId()));
		}
		if ($i->hasLocalUrl()) {
			$i->fileSystemDelete();
		}
	}
}