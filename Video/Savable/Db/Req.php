<?php
class G_Video_Savable_Db_Req
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
	public function save(G_MIE_Savable $mIE)
	{
		$sql = "INSERT INTO MIE (name, slug)";
	}
	
	public function delete(G_MIE_Savable $mIE)
	{
		
	}
}