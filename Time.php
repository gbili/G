<?php
/**
 * Helps in time conversion
 * @author gui
 *
 */
class G_Time
{
	const SECONDS = 1;
	const MINUTES = 2;
	const HOURS = 3;
	const DAYS = 4;
	const MONTHS = 5;
	const YEARS = 6;
	
	/**
	 * 
	 * @var unknown_type
	 */
	const HOURS_IN_MONTH = 720;
	
	/**
	 * human rememberable to seconds
	 * 
	 * @var unknown_type
	 */
	const UNIXTS_YEAR = 31536000;
	
	const UNIXTS_SEMESTER = 15552000;
	
	const UNIXTS_QUARTER = 7776000;
	
	const UNIXTS_MONTH = 2592000;
	
	const UNIXTS_WEEK = 604800;
	
	const UNIXTS_DAY = 86400;
	
	const UNIXTS_HOUR = 3600;
	
	const UNIXTS_MIN = 60;
	
	/**
	 * 
	 * @var unknown_type
	 */
	const HOURS_IN_YEAR = 8760;
	
	/**
	 * 
	 * @var unknown_type
	 */
	const HOURS_IN_DAY = 24;
	
	/**
	 * 
	 * @var unknown_type
	 */
	const MINUTES_IN_HOUR = 60;
	
	/**
	 * 
	 * @var unknown_type
	 */
	const SECONDS_IN_MINUTE = 60;
	
	/**
	 * 
	 * @var unknown_type
	 */
	const HOURS_TO_SECONDS = 3600;
	
	/**
	 * 
	 * @param unknown_type $int
	 * @param unknown_type $inputFormat
	 * @param unknown_type $outputFormat
	 * @return unknown_type
	 */
	public static function convertTo($int, $inputFormat, $outputFormat)
	{
		$secs = self::toSeconds($int, $inputFormat);
		switch ($outputFormat) {
			case self::SECONDS;
				return $secs;
			break;
			case self::MINUTES;
				$num = $secs / 60;
			break;
			case self::HOURS;
				$num = $secs / 3600;
			break;
			case self::DAYS;
				$num = $secs / 86400;
			break;
			case self::MONTHS;
				$num = $secs / 2592000;
			break;
			case self::YEARS;
				$num = $secs / 31536000;
			break;
			default;
				throw new G_Time_Exception("The input format is not supported");
			break;
		}
		if (!is_integer($num)) {
			$num = round($num);
		}
		return $num;
	}
	
	/**
	 * 
	 * @param unknown_type $int
	 * @param unknown_type $inputFormat
	 * @return unknown_type
	 */
	public static function toSeconds($int, $inputFormat)
	{
		if (!is_integer($int)) {
			throw new G_Time_Exception('');
		}
		
		switch ($inputFormat) {
			case self::SECONDS;
				return $int;
			break;
			case self::MINUTES;
				$int = $int * 60;
			break;
			case self::HOURS;
				$int = $int * 3600;
			break;
			case self::DAYS;
				$int = $int * 86400;
			break;
			case self::MONTHS;
				$int = $int * 2592000;
			break;
			case self::YEARS;
				$int = $int * 31536000;
			break;
			default;
				throw new G_Time_Exception("The input format is not supported");
			break;
		}
		return $int;
	}
	
}