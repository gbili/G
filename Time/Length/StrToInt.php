<?php
/**
 * To string returns an integer (wierd...)
 * @author gui
 *
 */
class G_Time_Length_StrToInt
extends G_Regex_Encapsulator_Abstract
{
	/**
	 * (non-PHPdoc)
	 * @see Url/G_Url_Abstract#toString()
	 */
	protected function _toString()
	{
		return (integer) (($this->hasHours())? $this->getHours(): '') . (($this->hasMinutes())? $this->getMinutes() : '') . $this->getSeconds();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasHours()
	{
		return $this->_hasPart('hours');
	}

	/**
	 * 
	 * @param $userInfo
	 * @return unknown_type
	 */
	public function setHours($h)
	{
		$this->_setPartWithDirtyData('hours', $h, false);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getHours()
	{
		return $this->_getPart('hours');		
	}
	
	/**
	 * 
	 * @param unknown_type $hostName
	 * @return unknown_type
	 */
	public function setMinutes($m)
	{
		$this->_setPartWithDirtyData('minutes', $m, false);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getMinutes()
	{
		return $this->_getPart('minutes');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasMinutes()
	{
		return $this->_hasPart('minutes');
	}
	
	/**
	 * 
	 * @param unknown_type $port
	 * @return unknown_type
	 */
	public function setSeconds($s)
	{
		$this->_setPartWithDirtyData('seconds', $s, false);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getSeconds()
	{
		return $this->_getPart('seconds');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Url/G_Url_Abstract#_setParts()
	 */
	protected function _setParts()
	{
		$this->_setPart('seconds', $this->getRegex()->getSeconds(), false);
		if ($this->getRegex()->hasMinutes()) {
			$norm = $this->_normalize();
			$this->_setPart('minutes', $norm['m'], false);
			if (isset($norm['h'])) {
				$this->_setPart('hours',$norm['h'], false);
			}
		}
	}
	
	/**
	 * Convert minutes >= 60 to hours
	 * @param unknown_type $minutes
	 * @return unknown_type
	 */
	private function _normalize()
	{
		$ret = array();
		$hours = ($this->getRegex()->hasHours())? (integer) $this->getRegex()->getHours() : 0;
		$minutes = (integer) $this->getRegex()->getMinutes();
		//minutes can be broken into hours
		if (60 <= $minutes) {
			$hours += ($minutes / 60);
			if (is_float($hours) && 0 < ($diff = $hours - floor($hours))) {
				$minutes = 60 * $diff;
				$minutes = round((float) $minutes);
			} else {
				$minutes = 0;
			}
		}
		//from here minutes = 0 or > 60
		//convert minutes 
		$minutes = (string) $minutes;
		if (mb_strlen($minutes) === 1) {
			$minutes = '0' . $minutes;
		}
		$ret['m'] = $minutes;
		//now only return hours if > 0
		if ($hours > 0) {
			$ret['h'] = (string) $hours;
		}
		return $ret;
	}
}