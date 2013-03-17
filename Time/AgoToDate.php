<?php
/**
 * 
 * @author gui
 *
 */
class G_Time_AgoToDate
{
	/**
	 * @var unknown_type
	 */
	private $_unixTimeStamp = null;
	
	/**
	 * 
	 * @param unknown_type $daysCount
	 * @return unknown_type
	 */
	public function __construct($input)
	{
		$a = new G_Time_Ago($input);
		$hoursCountFromNow = $a->toString();

		if (!is_numeric($hoursCountFromNow)) {
			throw new G_Exception('Input must be a numeric string or integer');
		}
		$secsFromNow = G_Time::toSeconds( (integer) $hoursCountFromNow, G_Time::HOURS);
		$this->_unixTimeStamp = time() - $secsFromNow; // unix time stam of input
	}
	
	/**
	 * 
	 * @param unknown_type $dateFormat
	 * @return unknown_type
	 */
	public function getDate($format = 'd-m-Y')
	{
		return date($format, $this->_unixTimeStamp);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getUnixTimeStamp()
	{
		return $this->_unixTimeStamp;
	}
	
}