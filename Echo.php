<?php
/**
 * This class is meant to allow to turn on
 * and off the output of a call to echo.
 * 
 * You can put different active states to
 * each function.
 * 
 * This allows you to treat a series of 
 * G_Echo::l1(in a different way if you use
 * different level<Num>() function to echo
 * 
 * 
 * @author gui
 *
 */
class G_Echo
{
	/**
	 * Level number mapped to activation state
	 * 
	 * @var unknown_type
	 */
	static private $_levels = array(1 => true, 2 => true, 3 => false, 4 => false, 5 => false);
	
	/**
	 * 
	 * @param $toOuting
	 * @param $levelNum
	 * @return unknown_type
	 */
	static private function _mother($toOut, $levelNum, $EOL = true)
	{
		if (true === self::$_levels[$levelNum]) {
			if (is_string($toOut)) {
				if (true === $EOL) {
					$toOut .= "\n";
				}
				echo $toOut;
			} else {
				print_r($toOut);
			}
		}
	}
	
	/**
	 * 
	 * @param unknown_type $level
	 * @return unknown_type
	 */
	static public function mute($level = null)
	{
		if (null !== $level) {
			if (is_array($level)) {
				foreach ($level as $l) {
					self::deactivateLevel($l);
				}
			} else {
				self::deactivateLevel($level);
			}
		} else { //deactivate all levels
			for ($i = 1; $i <= 5; $i++) {
				self::$_levels[$i] = false;
			}
		}
	}

	/**
	 * 
	 * @param unknown_type $levelNum
	 * @return unknown_type
	 */
	static public function activateLevel($levelNum)
	{
		self::_changeLevelState($levelNum, true);
	}

	/**
	 * 
	 * @param unknown_type $levelNum
	 * @return unknown_type
	 */
	static public function deactivateLevel($levelNum)
	{
		self::_changeLevelState($levelNum, false);
	}
	
	/**
	 * 
	 * @param unknown_type $levelNum
	 * @param unknown_type $activateBool
	 * @return unknown_type
	 */
	static private function _changeLevelState($levelNum, $activateBool)
	{
		self::_throwIfNotExistsLevel($levelNum);
		self::$_levels[(integer) $levelNum] = $activateBool;
	}
	
	/**
	 * 
	 * @param unknown_type $levelNum
	 * @return unknown_type
	 */
	static public function isActiveLevel($levelNum)
	{
		self::_throwIfNotExistsLevel($levelNum);
		return self::$_levels[(integer) $levelNum];
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	static private function _throwIfNotExistsLevel($levelNum)
	{
		if (!is_numeric($levelNum)) {
			throw new G_Echo_Exception('the level specified must be numeric');	
		}

		if (!isset(self::$_levels[(integer) $levelNum])) {
			throw new G_Echo_Exception('the level specified does not exist, add a function that supports it, given : ' . $levelNum);
		}
	}
	
	/**
	 * 
	 * @param unknown_type $toOut
	 * @return unknown_type
	 */
	static public function l1($toOut, $EOL = true)
	{
		self::_mother($toOut, 1, $EOL);
	}
	
	/**
	 * 
	 * @param $toOut
	 * @return unknown_type
	 */
	static public function l2($toOut, $EOL = true)
	{
		self::_mother($toOut, 2, $EOL);
	}
	
	/**
	 * 
	 * @param unknown_type $toOut
	 * @return unknown_type
	 */
	static public function l3($toOut, $EOL = true)
	{
		self::_mother($toOut, 3, $EOL);
	}
	
	/**
	 * 
	 * @param unknown_type $toOut
	 * @return unknown_type
	 */
	static public function l4($toOut, $EOL = true)
	{
		self::_mother($toOut, 4, $EOL);
	}
	
	/**
	 * 
	 * @param $toOut
	 * @return unknown_type
	 */
	static public function l5($toOut, $EOL = true)
	{
		self::_mother($toOut, 5, $EOL);
	}
}