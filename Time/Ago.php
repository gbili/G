<?php
class G_Time_Ago
extends G_Regex_Encapsulator_Abstract
{
	
	/**
	 * (non-PHPdoc)
	 * @see Url/G_Url_Abstract#toString()
	 */
	protected function _toString()
	{
		return $this->getHours();
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
	 * (non-PHPdoc)
	 * @see Url/G_Url_Abstract#_setParts()
	 */
	protected function _setParts()
	{
		$num = $this->getRegex()->getNumber();
		
		if ($this->getRegex()->hasMonths()) {
			$num = G_Time::convertTo($num, G_Time::MONTHS, G_Time::HOURS);
		} else if ($this->getRegex()->hasYears()) {
			$num = G_Time::convertTo($num, G_Time::YEARS, G_Time::HOURS);
		} else if ($this->getRegex()->hasDays()) {
			$num = G_Time::convertTo($num, G_Time::DAYS, G_Time::HOURS);
		}

		$this->_setPart('hours', $num, false);
	}
}