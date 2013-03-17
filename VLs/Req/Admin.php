<?php
/**
 * 
 * @author gui
 *
 */
class G_VLs_Req_Admin
extends G_VLs_Req
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
	 * @return unknown_type
	 */
	public function updateCatsWithThumbsTable()
	{	
		$rA = new G_Db_Req_Admin();
		$rA->emptyTables('G_Vid_CatWithThumb');
		
		$c = APP_IPP;
		return $this->getResultSet("INSERT INTO G_Vid_CatWithThumb (viewCount, vidsCount, imageLocalUrl, catName, catSlug, vidId, categoryId) 
											(SELECT vwcnt.viewCount AS viewCount, 
													vdcnt.vidsCount AS vidsCount, 
													i.localUrl AS imageLocalUrl, 
													vc.value AS catName, 
													vc.slug AS catSlug, 
													v.vidId AS vidId,
													v.categoryId AS categoryId
												FROM (SELECT categoryId, 
											 			 	 MAX(viewCount) AS viewCount
											 		 	FROM G_Vid 
											 			GROUP BY categoryId) AS vwcnt
											 		INNER JOIN (SELECT categoryId,
											 					   	   COUNT(*) AS vidsCount 
											 					 FROM G_Vid 
											 				 	 GROUP BY categoryId) AS vdcnt ON (vwcnt.categoryId = vdcnt.categoryId)  
											 		INNER JOIN G_Vid AS v ON (vwcnt.categoryId = v.categoryId) 
											 		INNER JOIN G_Vid_Category AS vc ON (vwcnt.categoryId = vc.elementId) 
											 		INNER JOIN G_Vid_Image AS i ON (v.imageId = i.imageId) 
											 	WHERE v.viewCount = vwcnt.viewCount 
											 	GROUP BY v.categoryId
											 	ORDER BY vc.viewCount DESC LIMIT 0, $c)");
	}
	
	/**
	 * 
	 * @param unknown_type $e
	 * @return unknown_type
	 */
	public function logException($e)
	{
		if ($e instanceof Exception) {
			$e = $e->__toString();
		}
		$this->insertUpdateData('INSERT INTO G_Exception (value) VALUES (:value)', array(':value' => $e));
	}
}