<?php
/**
 * 
 * @author gui
 *
 */
class G_Time_Length_IntToStr
{
	/**
	 * 
	 * @var unknown_type
	 */
	private $_hours = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_minutes = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_seconds = null;
	
	/**
	 * 
	 * @param unknown_type $input
	 * @return unknown_type
	 */
	public function __construct($input)
	{
		if (!is_numeric($input)) {
			throw new G_Exception('Input must be numeric');
		}
		$input = (string) $input;
		$this->_seconds = mb_substr($input, -2);
		if (2 < $l = mb_strlen($input)) {
			if ($l === 3) {
				$this->_minutes = mb_substr($input, -3, 1);
			} else {
				$this->_minutes = mb_substr($input, -4, 2);
			}
			if (mb_strlen($input) > 4) {
				$this->_hours = mb_substr($input, 0, -4);
			}
		}
	}

	/**
	 * 
	 * @return unknown_type
	 */
	public function hasHours()
	{
		return null !== $this->_hours;
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
	 * @return unknown_type
	 */
	public function getMinutes()
	{
		return $this->_minutes;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasMinutes()
	{
		return null !== $this->_seconds;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getSeconds()
	{
		return $this->_seconds;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function toString()
	{
		return (($this->hasHours())? $this->getHours() . ':' : '') . (($this->hasMinutes())? $this->getMinutes() . ':': '') . $this->getSeconds();
	}
}