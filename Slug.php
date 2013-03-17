<?php
/**
 * This converts any string to a url friendly string
 * 
 * G_Echo::l1(generate("Mess'd up --text-- just (to) stress /test/ ?our! `little` \\clean\\ url fun.ction!?-->"));
 * returns: messd-up-text-just-to-stress-test-our-little-clean-url-function
 *
 * G_Echo::l1(generate("Perch� l'erba � verde?", "'")); // Italian
 * returns: perche-l-erba-e-verde
 *
 * G_Echo::l1(generate("Peux-tu m'aider s'il te pla�t?", "'")); // French
 * returns: peux-tu-m-aider-s-il-te-plait
 * 
 * G_Echo::l1(generate("Custom`delimiter*example", array('*', '`')));
 * returns: custom-delimiter-example
 * 
 * G_Echo::l1(generate("My+Last_Crazy|delimiter/example", '', ' '));
 * returns: my last crazy delimiter example
 * 
 * also for other languages like turkish, swedish etc
 * 
 * @author gui
 *
 */
class G_Slug
{
	/**
	 * 
	 * @var unknown_type
	 */
	static private $_charsToPreReplace = array("'", "!", "?", ".", ":", ",", ";", ">", "<", "(", ")");
	
	/**
	 * 
	 * @var unknown_type
	 */
	static private $_inputCharSet = 'UTF-8';
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_slug;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_inputString;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_error = null;
	
	/**
	 * 
	 * @param unknown_type $textToSlugicize
	 * @return unknown_type
	 */
	public function __construct($textToSlugicize)
	{
		$this->_inputString = (string) $textToSlugicize;
		$this->_slug = self::generate($this->_inputString);
		//validate slug
		if (!($this->_isValid = self::isSlug($this->_slug))) { //match() returns true when it matches something, false otherwise
			$this->_error = 'The slug returned by G_Slug::generate() : "' . print_r($this->_slug, true) . '" is not considered valid by G_Slug_Regex_String : "' . print_r($regex->getRegexStringObject(false)->getFullRegex(), true) . '". The input was : "' . print_r($this->_inputString, true) .'"';
		}
	}
	
	/**
	 * 
	 * @param unknown_type $charSet
	 * @return unknown_type
	 */
	static public function setInputCharSet($charSet)
	{
		self::$_inputCharSet = (string) $charSet;
	}
	
	/**
	 * 
	 * @param $str
	 * @param $replace
	 * @param $delimiter
	 * @return unknown_type
	 */
	static public function generate($str, $replace = array(), $delimiter='-')
	{
		setlocale(LC_ALL, 'de_DE.UTF8');
		$str = G_Encoding::utf8Encode($str);//UTF-8
		
		//make pre-conversion replacements
		if (!empty($replace)) {
			$diff = array_diff($replace, self::$_charsToPreReplace);
			$replace = $replace + $diff;
		} else {
			$replace = self::$_charsToPreReplace;
		}
		//the characters that are replaced here, will be replaced by delimiter later
		//wherease if left as is, they will be replaced by empty string...
		$str = str_replace((array)$replace, ' ', $str);
		$str = trim($str);
		
		//remove front and trailing non alnum chars
		$r = new G_Regex($str, '^[^a-zA-Z0-9]*([a-zA-Z0-9].*?[a-zA-Z0-9])[^a-zA-Z0-9]*$');
		$str = $r->getMatches(1);
		
		//also replace locale specific symbols with ascii closest char
		$search = explode(",","ç,œ,æ,à,è,ì,ò,ù,á,é,í,ó,ú,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,ñ");
		$replace = explode(",","c,oe,ae,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,n");
		$str = str_replace($search, $replace, $str);
		
		//convert to slug
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = mb_strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
		return $clean;
	}
	
	/**
	 * Allow the user to pre replace some chars so they will be replaced
	 * by $delimiter in self::generate()
	 * 
	 * @param array $charsArray
	 * @return unknown_type
	 */
	static public function charsToPreReplace(array $charsArray = array())
	{
		$this->_charsToPreReplace = $charsArray;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getValue()
	{
		return $this->_slug;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getInputString()
	{
		return $this->_inputString;
	}
	
	/**
	 * 
	 * @param $value
	 * @return unknown_type
	 */
	public function isValid()
	{
		return $this->_isValid;
	}
	
	/**
	 * Allow the user to pass a string
	 * to see if it is a slug
	 * 
	 * @param unknown_type $value
	 * @return unknown_type
	 */
	static public function isSlug($value)
	{
		if (!is_string($value)) {
			throw new G_Slug_Exception('The value you passed to is valid, must be a string');
		}
		$regex = new G_Regex($value, new G_Slug_Regex_String());
		return $regex->match();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getError()
	{
		return $this->_error;
	}
}