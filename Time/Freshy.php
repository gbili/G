<?php
/**
 * This class transforms
 * a timestamp to a more friendly
 * tag depending on the timestamp
 * elderness
 * 
 * @author gui
 *
 */
class G_Time_Freshy
{
	/**
	 * map each self::$_states key
	 * to a timelaps in secs
	 * @var unknown_type
	 */
	static private $_statesLaps = array(G_Time::UNIXTS_DAY,
										G_Time::UNIXTS_WEEK,
										G_Time::UNIXTS_MONTH,
										G_Time::UNIXTS_QUARTER,
										G_Time::UNIXTS_SEMESTER,
										G_Time::UNIXTS_YEAR);
	/**
	 * Map each key to a tag
	 * 
	 * @var unknown_type
	 */
	static private $_states = array('just shot', 'kiosk' ,'couch' ,'closet', 'garage', 'dusty');
	
	/**
	 * The constructor timestamp
	 * tag
	 * 
	 * @var unknown_type
	 */
	private $_state = null;
	
	
	public function __construct($unixTimestamp)
	{
		/*if (!is_integer($unixTimestamp)) {
			throw new G_Time_Exception('unix timestamp must be an integer, given : ' . print_r($unixTimestamp));
		}*/
		$elapsedSeconds = time() - (integer) $unixTimestamp;
		if (0 > $elapsedSeconds) {
			throw new G_Time_Exception('unixtimestam must reference time from the past : ' . print_r($unixTimestamp));
		}
		
		foreach (self::$_statesLaps as $k => $secs) {
			if ($elapsedSeconds < $secs ) {
				$this->_state = self::$_states[$k];
				break;
			}
		}
		if (null === $this->_state) {
			$this->_state = self::$_states[5];
		}
	}
	
	/**
	 * 
	 * @param array $intervalsArray
	 * @return unknown_type
	 */
	static public function setIntervals(array $intervalsArray)
	{
		
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getState()
	{
		return $this->_state;
	}
}