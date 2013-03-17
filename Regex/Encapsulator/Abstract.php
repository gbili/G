<?php
/**
 * G_Regex_String defines the regular expression including groups
 * G_Regex_Abstract get an input string and use the G_Regex_String to provide an output as groups
 * G_Regex_Abstract subclasses typecast which G_Regex_String subclass, and map the group numbers to meaningful entites with methods
 * G_Regex_Encapsulator allows any given class to use the functionality of the Regex "module" by
 * encapsulating the whole thing together.
 * I.E. : 
 * So it accepts the input string in the constructor
 * then generates the appropriate G_Regex and G_Regex_String subclasses and automatically ties to validate input
 * you can specify to throw exception if the input is not considered to be valid by the regex.
 * 
 * Once validation has taken place, and the regex matched something, the abstract function _setParts() is 
 * called. The subclass can then set the parts individually with _setPart('partName', value, storeAsObject=false)
 * It can access the regex output with $this->getRegex()->get...() and set each part. If you have
 * defined human understandable methods for accessing the matched groups in your G_<sub_class>_Regex
 * then you can call them like : $this->_setPart('brand', $this->getRegex()->getBrand()) or call
 * $this->_setPart('brand', $this->getRegex()->getGroupNumber(1))
 * The latter one has the pitfail that you may be trying to get a group number that does not exist
 * if you had defined human accessible methods you can check there if the goup exist and return a default
 * value in case it does not.
 * 
 * _setPart() should only be used on a sanitized context i.e. with the validated output if you want to
 * let the user change the value of a part (from a public function) with new input (not yet validated input)
 * then you can use _setPartWithDirtyData() this will force to revalidate the new input string, obtained from
 * the combination of already validated parts and the new dirty parts with the method $this->toString();
 * 
 * You must use the directory tree structure of /YourClass/Regex/String.php, /YourClass/Regex.php to make
 * the auto regex instantiation work
 * 
 * @author gui
 *
 */
abstract class G_Regex_Encapsulator_Abstract
{
	/**
	 * 
	 * @var unknown_type
	 */
	static public $throwExceptionIfNotValid = true;
	
	/**
	 * This is needed to avoid memory allocation limit
	 * excess
	 * 
	 * @var unknown_type
	 */
	protected $_skipToStringValidation = false;
	
	/**
	 * 
	 * @var string
	 */	
	private $_inputString = null;
	
	/**
	 * 
	 * @var boolean
	 */
	private $_isValidated = false;
	
	/**
	 * 
	 * @var G_Regex subclass
	 */
	protected $_regex = null;
	
	/**
	 * Is also used as valid check
	 * 
	 * @var unknown_type
	 */
	private $_matchedSomething = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_parts = array();
	
	/**
	 * Populates the class members
	 * 
	 * @param unknown_type $url
	 * @return unknown_type
	 */
	public function __construct($inputString)
	{
		$this->_inputString = (string) $inputString;

		if (!$this->isValid() && !self::$throwExceptionIfNotValid == true) {
		 	throw new G_Exception('Error : input is not valid, input string: ' . print_r($this->_inputString, true) . ' preg_match result: ' . print_r($pregRes, true));
		}
	}
	
	/**
	 * Return the dirty url string 
	 * passed as constructor param
	 * 
	 * @return unknown_type
	 */
	public function getString()
	{
		return $this->_inputString;
	}
	
	/**
	 * Tells itf the url string apears to be compliant with its
	 * reciproque regex under /Miner/Regex/Url/... 
	 * 
	 * @param $urlString
	 * @return unknown_type
	 */
	public function isValid()
	{
		//return if allready checked
		if (true === $this->_isValidated && null !== $this->_matchedSomething) {
			return $this->_matchedSomething;
		}
		//allow to validate from the peaces set from setter (like setPath()) or if not available from $this->_urlString
		if (!empty($this->_parts)) {
			//avoid memory allocation limit excess
			$this->_skipToStringValidation = true;
			$this->getRegex()->setInputString($this->toString());
			$this->_skipToStringValidation = false;
		}
		if ($this->_matchedSomething = $this->getRegex()->match()) {
			//call subclass function to set each part from the regex object matches
			$this->_setParts();//modifies $this->_matchedSomething to false when one part is not valid (does not match anything)
		} else if (!$this->getRegex()->isValid()) {
			throw new G_Exception('The regex return by class : ' . get_class($this->getRegex()) . ' is not valid.');
		}
		$this->_isValidated = true;
		//set parts will alter the is valid value so make sure we dont overwrite it
		return $this->_matchedSomething;
	}
	
	/**
	 * If any of the parts is not valid
	 * then the whole container is not valid
	 * recursive...
	 * 
	 * @param string $name
	 * @param string $value
	 * @return unknown_type
	 */
	protected function _setPart($name, $value, $storeAsObject = true)
	{
		if (true === $storeAsObject) {
			$class = get_class($this) . '_' . ucfirst(mb_strtolower($name));
			if (is_string($value)) {
				$value = new $class($value);
			}
			if (!($value instanceof $class)) {
				throw new G_Exception('The value must be an instance of : ' . $class . ' when $storeAsObject is true');
			}
			if (!$value->isValid()) { //only alter when not valid
				$this->_matchedSomething = false;
			}
		} else if (is_object($value)) {
			throw new G_Exception('The value is suposed to be a string not an object, given : ' . print_r($value, true) . 'set the value to a string or change the 3 param to true');
		}
		$this->_parts[(string) $name] = $value;
	}
	
	/**
	 * Same as set part but use this when the data
	 * comes from user input as in a public setElement()
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $value
	 * @param unknown_type $storeAsObject
	 * @return unknown_type
	 */
	protected function _setPartWithDirtyData($name, $value, $storeAsObject = true)
	{
		$this->_isValidated = false;
		$this->_setPart($name, $value, $storeAsObject);
	}
	
	/**
	 * 
	 * @param unknown_type $name
	 * @return unknown_type
	 */
	protected function _hasPart($name)
	{
		return isset($this->_parts[$name]);
	}
	
	/**
	 * 
	 * @param unknown_type $name
	 * @return unknown_type
	 */
	protected function _getPart($name)
	{
		if (!isset($this->_parts[$name])) {
			throw new G_Url_Abstract_Exception('Trying to get part with name : ' . (string) $name . ' in : ' . get_class($this) . ' and it is not set');
		}
		return $this->_parts[$name];
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	protected function _getParts()
	{
		return $this->_parts;
	}
	
	/**
	 * If the regex object matches something, then this
	 * method is called so the subclass can set its
	 * parts from the regex results and name each
	 * part as it wants.
	 * 
	 * @return unknown_type
	 */
	abstract protected function _setParts();
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getRegex()
	{
		if (null === $this->_regex) {
			$regexClassName = get_class($this);
			$regexClassName .= '_Regex';
			$regexStrClassName = $regexClassName . '_String';
			$this->_regex = new $regexClassName($this->_inputString, new $regexStrClassName()); 
		}
		return $this->_regex;
	}
	
	/**
	 * 
	 * @param G_Regex_Abstract $regex
	 * @return unknown_type
	 */
	public function setRegex($regex)
	{
		$class = get_class($this) . '_Regex';
		if (!($regex instanceof $class)) {
			throw new G_Exception('The regex instance must be of type : ' . $class);
		}
		$this->_isValidated = false;
		$this->_regex = $regex;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function isValidated()
	{
		return (true === $this->_isValidated);
	}
	
	/**
	 * Retruns a normalized url
	 * 
	 * @return unknown_type
	 */
	final public function toString()
	{
		if (!$this->_skipToStringValidation && !$this->isValid()) {
			throw new G_Exception('Cannot call toString() if G_Url_Regex did not match anything');
		}
		return $this->_toString();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	abstract protected function _toString();
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function toArray($oneLevel = false)
	{
		$array = array();
		if (!empty($this->_parts)) {
			foreach ($this->_parts as $name => $part) {
				if ($part instanceof self) {
					$part = $part->toArray($oneLevel);	
				}
				if (true === $oneLevel && is_array($part)) {
					foreach ($part as $subName => $subPart) {
						$array[$subName] = $subPart;
					}
				} else {
					$array[$name] = $part;
				}
			}
		}
		return $array;
	}
}