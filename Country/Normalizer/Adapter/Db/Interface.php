<?php
interface G_Country_Normalizer_Adapter_Db_Interface
{
	/**
	 * return the next block of records
	 * depending on fetch count
	 * 
	 * @param $fetchCount
	 * @return unknown_type
	 */
	public function getBlock($fetchCount);
	
	/**
	 * Get all records at once
	 * 
	 * @return unknown_type
	 */
	public function getAll();
	
	/**
	 * Get one record
	 * 
	 * @param unknown_type $fetchCount
	 * @return unknown_type
	 */
	public function getOne($fetchCount);
	
	/**
	 * 
	 * @return numerically indexed array ex: array(0=>'France',1=>'Romania'..)
	 */
	public function getCountries();
	
	/**
	 * 
	 * @param $str
	 * @return boolean
	 */
	public function isSupportedCountry($str);
}