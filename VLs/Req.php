<?php
/**
 * Videos List
 * 
 * @author gui
 *
 */
class G_VLs_Req
extends G_Db_Req
{
	/**
	 * 
	 * @var G_Db_Req
	 */
	private $_requestor = null;
	
	/**
	 * Default filter
	 * for videos
	 * 
	 * @var G_VLs_Filter
	 */
	static private $_vFilter = null;
	
	/**
	 * Default filter for cats
	 * 
	 * @var G_VLs_Filter
	 */
	static private $_cFilter = null;
	
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
	 * @param G_VLs_Filter $f
	 * @return void
	 */
	static public function setCFilter(G_VLs_Filter $f)
	{
		self::$_cFilter = $f;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	static public function getCFilter()
	{
		if (null === self::$_cFilter) {
			self::$_cFilter = new G_VLs_Filter();	
		}
		return self::$_cFilter;
	}
	
	/**
	 * 
	 * @param unknown_type $value
	 * @return unknown_type
	 */
	static public function setVFilter(G_VLs_Filter_Vid $f)
	{
		self::$_vFilter = $f;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	static public function getVFilter()
	{
		if (null === self::$_vFilter) {
			self::$_vFilter = new G_VLs_Filter_Vid();	
		}
		return self::$_vFilter;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getCategories(G_VLs_Filter $f = null)
	{
		return $this->getResultSet("SELECT c.value AS name,
													 c.slug AS slug,
													 COUNT(v.vidId) AS itemsCount
											  FROM G_Vid_Category AS c
											  	INNER JOIN G_Vid AS v ON (c.elementId = v.categoryId)
											  GROUP BY c.elementId 
											  ORDER BY c.value ASC");
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getVideos(G_VLs_Filter_Vid $f = null)
	{
		if (null === $f) {	
			$f = self::getVFilter();
		}
		$where = '';//@todo adapt the query to narrow results
		$values = array();
		if ($f->hasCatSlug()) {
			$where = 'WHERE vc.slug = :categorySlug';
			$values[':categorySlug'] = $f->getCatSlug();
		}
		
		switch ($f->getOrderBy()) {
			case G_VLs_Filter::NAME;
				$r = 't.value';
			break;
			case G_VLs_Filter::POPULARITY;
				$r = 'v.viewCount';
			break;
			case G_VLs_Filter::RANDOM;
				$r = 'RAND()';
			break;
			case G_VLs_Filter::TIME_LENGTH;
				$r = 'v.timeLength';
			break;
			case G_VLs_Filter::DATE;
				$r = 'v.date';
			break;
		}
		$sql = "SELECT v.vidId AS id,
						t.value AS title, 
						 CONCAT(sh.host, '', s.path) AS vidUrl,
						 sh.host AS hostUrl,
						 sh.hFName AS hostName,
						 i.localUrl AS imageLocalUrl,
						 vc.value AS catName,
						 vc.slug AS catSlug,
						 SEC_TO_TIME(v.timeLength) AS timeLength,
						 v.date AS unixTS
				 FROM G_Vid AS v 
				 	INNER JOIN G_Vid_Title AS t ON (v.titleId = t.elementId)
				 	INNER JOIN G_Source AS s ON (v.sourceId = s.sourceId)
				 		INNER JOIN G_Source_Host AS sh ON (s.hostId = sh.hostId)
				 	INNER JOIN G_Vid_Image AS i ON (v.imageId = i.imageId)
				 	INNER JOIN G_Vid_Category AS vc ON (v.categoryId = vc.elementId)
				 $where
				 GROUP BY t.value
				 ORDER BY $r DESC LIMIT {$f->getStartItem()}, {$f->getIPP()}";
		return $this->getResultSet($sql, $values);
	}
	
	/**
	 * 
	 * @param unknown_type $id
	 * @return unknown_type
	 */
	public function getVideo($id)
	{
		$sql = "SELECT v.vidId AS id,
					 t.value AS title, 
					 CONCAT(sh.host, '', s.path) AS vidUrl,
					 sh.host AS hostUrl,
					 sh.hFName AS hostName,
					 i.localUrl AS imageLocalUrl,
					 vc.value AS catName,
					 vc.slug AS catSlug,
					 SEC_TO_TIME(v.timeLength) AS timeLength,
					 v.date AS unixTS
			 FROM G_Vid AS v 
			 	INNER JOIN G_Vid_Title AS t ON (v.titleId = t.elementId)
			 	INNER JOIN G_Source AS s ON (v.sourceId = s.sourceId)
			 		INNER JOIN G_Source_Host AS sh ON (s.hostId = sh.hostId)
			 	INNER JOIN G_Vid_Image AS i ON (v.imageId = i.imageId)
			 	INNER JOIN G_Vid_Category AS vc ON (v.categoryId = vc.elementId)
			 WHERE v.vidId = :id";
		return $this->getResultSet($sql, array(':id' => (integer) $id));
	}
	
	/**
	 * 
	 * @param unknown_type $id
	 * @return unknown_type
	 */
	public function existsVideo($id)
	{
		return $this->existsElement("SELECT v.vidId AS id FROM G_Vid AS v WHERE v.vidId = :id",  array(':id' => (integer) $id));
	}
	
	/**
	 * 
	 * @param unknown_type $slug
	 * @return unknown_type
	 */
	public function existsCategory($slug)
	{
		return $this->existsElement("SELECT c.elementId AS id FROM G_Vid_Category AS c WHERE c.slug = :slug",  array(':slug' => $slug), 'id', true);
	}
	
	/**
	 * 
	 * @param unknown_type $catSlug
	 * @return unknown_type
	 */
	public function getVideosCount($catSlug)
	{
		$r = $this->getResultSet("SELECT COUNT(*) AS vidCount
											FROM G_Vid AS v 
												INNER JOIN G_Vid_Category AS vc ON (v.categoryId = vc.elementId) 
											WHERE vc.slug = :catSlug",
											array(':catSlug' => $catSlug));
		if (false === $r) {
			return 0;
		}
		return $r[0]['vidCount'];
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getCategoriesWithThumbs(G_VLs_Filter $f = null)
	{
		if (null === $f) {	
			$f = self::getVFilter();
		}

		switch ($f->getOrderBy()) {
			case G_VLs_Filter::NAME;
				$r = 'c.value';
			break;
			case G_VLs_Filter::POPULARITY;
				$r = 'vwcnt.viewCount';
			break;
			case G_VLs_Filter::RANDOM;
				$r = 'RAND()';
			break;
			case G_VLs_Filter::TIME_LENGTH;
				throw new Exception('not allowed');
			break;
			case G_VLs_Filter::DATE;
				throw new Exception('not allowed');
			break;
			default;
				$r = 'vdcnt.vidsCount';
			break;
		}
		
		return $this->getResultSet("SELECT viewCount,
									vidsCount,
									imageLocalUrl,
									catName,
									catSlug,
									vidId,
									categoryId
								FROM G_Vid_CatWithThumb");
	}
	
	/**
	 * 
	 * @param unknown_type $videoId
	 * @return unknown_type
	 */
	public function increaseCategoryViewCount($catSlug)
	{
		if ($idOrFalse = $this->existsCategory($catSlug)) {
			$this->insertUpdateData("UPDATE G_Vid_Category SET viewCount = viewCount + 1 WHERE elementId = :id",
											array(':id' => $idOrFalse));
		}
	}
	
	/**
	 * 
	 * @param unknown_type $videoId
	 * @return unknown_type
	 */
	public function increaseVideoViewCount($videoId)
	{
		$videoId = (integer) $videoId;
		if ($this->existsVideo($videoId)) {
			$this->insertUpdateData("UPDATE G_Vid SET viewCount = viewCount + 1 WHERE vidId = :id", array(':id' => $videoId));
		}
	}
}