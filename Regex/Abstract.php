<?php
/**
 * This class wraps the php preg_match and preg_match_all functions
 * It adds some commodities such as avoiding to
 * pass a $matches variable.
 * Plus it helps in retrieving the preg_match_all elements in an ordered
 * fashion with the function getNextMatch(). It remembers all the matches
 * in a preg_match_all and is able to return one match element at a time.
 * You can use getMatches() to get the whole set of matches of a preg_match_all 
 * or a preg_match. If you specify an index
 * 
 * @author gui
 *
 */
class G_Regex_Abstract
{
	/**
	 * Tells whether preg match found something
	 * or if there was an error
	 * 
	 * @var boolean
	 */
	private $_isValid;
	
	/**
	 * Tells whether the isValid member is
	 * syncronized with the input and regex str
	 * 
	 * @var unknown_type
	 */
	private $_isValidated;
	
	/**
	 * There are the two regex functions:
	 * preg_match and preg_match_all. As
	 * the results are retrieved from the
	 * same function, this will help the
	 * function in determining how to return
	 * a result.
	 * 
	 * @var unknown_type
	 */
	private $_isMatchAll;
	
	/**
	 * The string that needs to be validated
	 * 
	 * @var unknown_type
	 */
	private $_inputString;
	
	/**
	 * A subclass of G_Regex_String_Abstract
	 * 
	 * @var G_Regex_String_Abstract
	 */
	private $_regexStringObject;
	
	/**
	 * all matches of preg_match_all
	 * 
	 * @var array
	 */
	private $_matches;
	
	/**
	 * Matches of a preg_match call
	 * or the match being treated by getNextMatch in a preg_match_all
	 * 
	 * @var unknown_type
	 */
	private $_currentMatch;
	
	/**
	 * Contains all the elements that where shifted
	 * when calling getNextMatch (only if using matchAll())
	 * @var unknown_type
	 */
	private $_matchesShifted;
	
	/**
	 * true if regex is valid and matches or current match as something in it
	 * @var unknown_type
	 */
	private $_matchedSomething;
	
	/**
	 * 
	 * @param unknown_type $input
	 * @param G_Regex_String_Abstract $regexStringObject
	 * @return unknown_type
	 */
	public function __construct($input, G_Regex_String_Abstract $regexStringObject)
	{
		//store the input string
		$this->_inputString = (string) $input;
		$this->_regexStringObject = $regexStringObject;
		$this->_isValidated = false;
		$this->_isMatchAll = null;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getRegexStringObject($iWillEditRegexStringObject = true)
	{
		//isValidated will only be true if it has already been validated and the user wont edit regex str obj
		$this->_isValidated = (!$iWillEditRegexStringObject && $this->_isValidated);
		return $this->_regexStringObject;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function setRegexStringObject(G_Regex_String_Abstract $regexStringObject)
	{
		$this->_regexStringObject = $regexStringObject;
		$this->_isValidated = false;
		return $this;
	}
	
	/**
	 * The string against which the regex pattern (in regexStringObject) will be applied
	 * @return unknown_type
	 */
	public function getInputString()
	{
		return $this->_inputString;
	}
	
	/**
	 * The string against which the regex pattern will be applied
	 * @return unknown_type
	 */
	public function setInputString($input)
	{
		if (!is_string($input)) {
			throw new G_Regex_Exception('Error : The first param must be a string given : ' . print_r($input, true));
		}
		$this->_inputString = $input;
		$this->_isValidated = false;
		return $this;
	}
	
	/**
	 * This will call match()
	 * 
	 * @return unknown_type
	 */
	public function isValid()
	{
		if (false === $this->_isValidated) {
			throw new G_Regex_Exception('You must call match() or matchAll() before isValid()');
		}
		return $this->_isValid;
	}
	
	/**
	 * Run a preg_match function to the regex
	 * and keep the results.
	 * Use getMatches() to retrieve them
	 * 
	 * Overwrites a matchAll() call
	 * 
	 * @return boolean (tells whether there is a match or not)
	 */
	public function match()
	{
		//if has not been validated validate (or the function called was matchAll do it again)
		if ($this->_isValidated === false || $this->_isMatchAll === true) {
			$this->_match(preg_match($this->getRegexStringObject()->getFullRegex(), $this->getInputString(), $this->_currentMatch), false);
		}
		return $this->_matchedSomething;
	}
	
	/**
	 * Run a preg_match_all function to the regex
	 * and keep the results
	 * Use getMatches() to retrieve them
	 * and shiftMatch() to point to the next match
	 * 
	 * Overwrites a match() call
	 * 
	 * @return boolean (tells whether there are matches or not)
	 */
	public function matchAll()
	{
		//if has not been validated validate (or the function called was a simple match() validate it again)
		//or stated reversly : if it was validated with a match all, don't do it again
		if ($this->_isValidated === false || $this->_isMatchAll === false ) {
			$this->_match(preg_match_all($this->getRegexStringObject()->getFullRegex(), $this->getInputString(), $this->_matches, PREG_SET_ORDER), true);
			//make sure pointer is on first element
			//$a = $this->_matches;
			reset($this->_matches);
			//$this->_matches = $a;
			//fill current match
			$this->goToNextMatch();
		}
		return $this->_matchedSomething;
	}
	
	/**
	 * Avoid code duplication
	 * 
	 * @return unknown_type
	 */
	private function _match($res, $matchAll = false)
	{
		$this->_isValid = (false !== $res);//if res = 0 or 1 the regex is valid
		$this->_matchedSomething = (0 < $res);//if res > 0 then it matched something		
		$this->_isValidated = true;
		$this->_isMatchAll = $matchAll;
	}
	
	/**
	 * Only call this if you used the matchAll
	 * function or if you did not validate. Otherwise it
	 * will throw up.
	 * 
	 * This function will shift the _matches array and put
	 * the value (which is a one level array of matching group => value like in a preg_match function) in $this->_currentMatch.
	 * It will also keep the shifted values in _matchesShifted so all matches can be retrieved any time with
	 * an addition : $this->_matchesShifted + $this->_matches
	 * 
	 * @return boolean
	 */
	public function goToNextMatch()
	{
		$this->_preGet('goToNextMatch');
		//ensure it is match all
		if ($this->_isMatchAll === false) {
			throw new G_Regex_Exception('You cannot call, G_Regex::goToNextMatch() if you called G_Regex::match() previously instead of matchAll()');
		}
		//ensure there are matches
		if (false === $this->_matchedSomething) {
			throw new G_Regex_Exception('there are no matches');
		}
		//move the pointer
		//tell the caller if there are more matches
		return (boolean) list($key, $this->_currentMatch) = each($this->_matches);
		
	}

	/**
	 * Defaults to use match() instead of matchAll()
	 * So call expicitely matchAll() before this if needed
	 * It will return all the matches from a preg_match or preg_match_all
	 * or if parameter is given, it will return the value of the specified group
	 * in $group (for the current match, when isMatcAll true)
	 * Use getCurrent()
	 * 
	 * @param integer $group
	 * @param boolean $getAllMatches if true when match all, it will return _matches, otherwise returns _currentMatch
	 * @return array | string | null
	 */
	public function getMatches($group = null, $getAllMatches = true)
	{
		$this->_preGet('getMatches');
		//return value of key in matches
		if (null !== $group) {
			if (false === $this->hasGroupNumber($group)) {
				throw new G_Regex_Exception('You are trying to get a group that does not exist. group : ' . print_r($group, true) . ', matches : ' . print_r($this->_currentMatch, true));
			}
			return $this->_currentMatch[$group];
		}
		//preg_match_all
		if (true === $this->_isMatchAll
		 && true === $getAllMatches) {
			return $this->_matches;
		}
		return $this->_currentMatch;
	}
	
	/**
	 * Proxy
	 * @return unknown_type
	 */
	public function getCurrentMatch()
	{
		return $this->getMatches(null, false);
	}
	
	/**
	 * Tells whether the group wth number $group exists in currentMatch
	 * 
	 * @param integer $group
	 * @return unknown_type
	 */
	public function hasGroupNumber($group) {
		$this->_preGet('hasGroupNumber');
		if (false === is_int($group)) {
			throw new G_Regex_Exception('$group argument must be of type integer given : ' . print_r($group,true));
		}
		return (isset($this->_currentMatch[$group]) && '' !== $this->_currentMatch[$group]);
	}
	
	/**
	 * 
	 * @param unknown_type $part
	 * @return unknown_type
	 */
	protected function _preGet($funcName = 'getMatches')
	{
		//ensure it has been validated (match() or matchAll() was called)
		if (false === $this->_isValidated) {
			//if not, default to call match()
			if (false === ((true === $this->_isMatchAll)? $this->matchAll() : $this->match())) {
				throw new G_Regex_Exception('The regex didn\'t match anything : ' . print_r($this, true));
			}
		}
		if (!$this->_isValid) {
			$msg = 'The input string is not valid cannot call ' . (string) $funcName . '()';
			throw new G_Url_Regex_Exception($msg);
		}
	}
}