<?php
/**
 * This class is will generate the regex pattern to match a date
 * There are a lot of things to rethink like the fact that the 3 parts
 * of the date must be set
 * 
 * 
 * @see G_Date_Regex for the implementation in a preg_match function
 * 
 * @author gui
 *
 */
class G_Date_Regex_String
extends G_Regex_String_Abstract
{
	/**
	 * these numbers corresponds to the position
	 * of parts in self::$_originalParts
	 * 
	 * @var unknown_type
	 */
	const YEAR  = 0; 
	const MONTH = 1;
	const DAY	= 2;
	
	/**
	 * The parts are implemented as an array to
	 * ease reorganisation
	 * Never touch this array order it is related to
	 * the class constants and used in reorderParts()
	 * 
	 * @var unknown_type
	 */
	static private $_originalParts = array('(?:1[0-9]|20)\d\d',//year
										   '0[1-9]|1[012]',//month
										   '0[1-9]|[12][0-9]|3[01]');//day

	/**
	 * Contains the very default regex
	 * 
	 * @var unknown_type
	 */
	protected $_defaultRegex = '((?:19|20)\d\d)(?:([- /.])(0[1-9]|1[012])\2(0[1-9]|[12][0-9]|3[01]))?';

	/**
	 * The parts reorganized in
	 * the desired order
	 * 
	 * @var unknown_type
	 */
	private $_parts = array();
	
	/**
	 * By default all parts are required
	 * 
	 * @var unknown_type
	 */
	private $_optionalParts = array(false, false, false);
	
	/**
	 * Contains the order of the regex parts
	 * 
	 * @var unknown_type
	 */
	private $_order;
	
	/**
	 * Contains the cardinality of $_order
	 * 
	 * @var unknown_type
	 */
	private $_orderCount;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_separator = '[- /.]';
	
	/**
	 * Maps each part to its group in the
	 * matches array of the preg match
	 * 
	 * @var unknown_type
	 */
	private $_matchesGroupsMap;
	
	/**
	 * 
	 * @param unknown_type $regex
	 * @return unknown_type
	 */
	public function __construct($regex = null, array $order = array())
	{
		parent::__construct($regex);
		//allow reordering of parts
		if (empty($order)) {
			//if no reordering is needed set the order to default
			$this->_order = array(self::YEAR, self::MONTH, self::DAY);
			$this->_orderCount = 3;
			//this works because $_original parts keys are the same as constants
			$this->_parts = self::$_originalParts;
		} else {
			$this->setOrder($order);
		}
		//if the order count is < 2 then set the parts that are not in order to optional
		//if ($this->_orderCount) {
			
		//}
		
	}
	
	/**
	 * This allows some parts to be optional
	 * 
	 * @param array(G_Date_Regex_String::PART=> true, G_Date_Regex_String::PART=> false etc.) $part
	 * @return unknown_type
	 */
	public function setOptionalParts(array $parts)
	{
		$optionalCount = 0;//will contain the number of parts that are set to optional
		foreach ($parts as $key => $value) {
			if (!isset($this->_parts[$key])) {
				throw new G_Date_Regex_String_Exception('Error : you cannot define optionality on missing parts.');
			}
			$this->_optionalParts[$key] = (boolean) $value;
			if (true == $value) {
				$optionalCount++;//count the number of parts that are optional
			}
		}
		//if the number of optional parts is greater or the same as the number of parts, throw up
		if (count($parts) <= $optionalCount) {
			throw new G_Date_Regex_String_Exception('Error : all your parts cannot be optional, there must at least be one not optional given : ' . print_r($this->_partsOptional,true));
		}
		$this->_setRegexStringAsNotUpToDate();
		return $this;
	}
	
	/**
	 * Use this if you want for example a
	 * month in letters rather than in digits
	 * 
	 * @param unknown_type $month
	 * @return unknown_type
	 */
	public function setMonth($regex)
	{
		$this->_parts[self::MONTH] = (string) $regex;
		$this->_setRegexStringAsNotUpToDate();
		return $this;
	}
	
	/**
	 * 
	 * @param unknown_type $day
	 * @return unknown_type
	 */
	public function setDay($regex)
	{
		$this->_parts[self::DAY] = (string) $regex;
		$this->_setRegexStringAsNotUpToDate();
		return $this;
	}
	
	/**
	 * 
	 * @param unknown_type $year
	 * @return unknown_type
	 */
	public function setYear($regex)
	{
		$this->_parts[self::YEAR]= (string) $regex;
		$this->_setRegexStringAsNotUpToDate();
		return $this;
	}
	
	/**
	 * Change the separator
	 * 
	 * @param unknown_type $separator
	 * @return unknown_type
	 */
	public function setSeparator($separator)
	{
		$this->_separator = (string) $separator;
		$this->_setRegexStringAsNotUpToDate();
		return $this;
	}
	
	/**
	 * This is the order of the regex groups
	 * !!! Don't change the newOrder checks otherwise rest will crash
	 * 
	 * @param array $order
	 * ex : array(self::DAY, self::MONTH, self::YEAR);
	 * @return unknown_type
	 */
	public function setOrder(array $newOrder)
	{
		//only one element is required
		if (!isset($newOrder[0]) || $newOrder[0] > 2) {
			throw new G_Regex_String_Exception('Error : reordering is not possible, because the param array is not well formed, expecting array(G_Date_Regex_String::DAY,G_Date_Regex_String::G_Date_Regex_String::YEAR) or something like that.. given : ' . print_r($newOrder, true));
		}
		$count = count($newOrder);
		//the new order array cant be out of these bindings
		if ($count > 3 || $count === 0) {
			throw new G_Date_Regex_String_Exception('Error : $newOrder cannot have more elements than 3 or 0 given : ' . print_r($newOrder, true));
		}
		//from the order set the parts array
		//from now on the parts array only contains what is defined in order
		$partsCopy = $this->_parts;
		$this->_parts = array(); //empty the parts array
		for ($i = 0; $i < $count; $i++) {
			//ensure order is serially indexed
			if (!isset($newOrder[$i])) {
				throw new G_Date_Regex_String_Exception('Error : $newOrder must be serially indexed : ' . print_r($newOrder, true));
			}
			//now set the parts array
			if (isset($partsCopy[$newOrder[$i]])) {//try to get the latest one
				$this->_parts[$newOrder[$i]] = $partsCopy[$newOrder[$i]];
			} else {//if not available retrieve the default one
				$this->_parts[$newOrder[$i]] = self::$_originalParts[$newOrder[$i]];
			}	
		}
		$this->_order = $newOrder;
		$this->_orderCount = $count;
		//force regex to update itself
		$this->_setRegexStringAsNotUpToDate();
		return $this;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getOrder()
	{
		return $this->_order;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getOrderCount()
	{
		return $this->_orderCount;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getMatchesGroupsMap()
	{
		if (!$this->_isRegexStringUpToDate()) {
			$this->_update();
		}
		return $this->_matchesGroupsMap;
	}

	/**
	 * Only called when getRegex() is called and it is not up to date
	 * 
	 * (non-PHPdoc)
	 * @see Common/Regex/G_Regex_String_Abstract#getUpdatedRegex()
	 */
	protected function _update()
	{
		$orderedParts = array();
		foreach ($this->_order as $partKey) {
			$orderedParts[] = (true === $this->_optionalParts[$partKey])? '(' . $this->_parts[$partKey] . ')?' : '(' . $this->_parts[$partKey] . ')';
		}
		switch ($this->_orderCount) {
			case 1;
				$regex = $orderedParts[0];
				$this->_matchesGroupsMap[$this->_order[0]] = 1;
				break;
			case 2;
				$regex = $orderedParts[0] . $this->_separator . $orderedParts[1];
				$this->_matchesGroupsMap[$this->_order[0]] = 1;
				$this->_matchesGroupsMap[$this->_order[1]] = 2;
				break;
			case 3;
				$regex = $orderedParts[0] . "($this->_separator)" . $orderedParts[1] . '\2' . $orderedParts[2];	
				$this->_matchesGroupsMap[$this->_order[0]] = 1;
				$this->_matchesGroupsMap[$this->_order[1]] = 3;
				$this->_matchesGroupsMap[$this->_order[2]] = 4;
				break;
			default;
				throw new G_Date_Regex_String_Exception('Error : programming error, the count should be 1,2 or 3. It has somehow made its way being different than that, order given : ' . print_r($this, true));
				break;
		}
		$this->setRegex($regex);
	}
}