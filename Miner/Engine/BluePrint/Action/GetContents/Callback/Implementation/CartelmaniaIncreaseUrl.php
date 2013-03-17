<?php
class CartelmaniaIncreaseUrl
extends G_Miner_Engine_BluePrint_Action_GetContents_Callback_Abstract
{
	/**
	 * 
	 * @param unknown_type $args
	 * @return unknown_type
	 */
	protected function _callback($args)
	{
		$url = new G_Url($args[0]);
		$path = $url->getPath();
		$regex = new G_Regex($path, new G_Regex_String_Generic('[^.\d]+(\d+)\.\w+'));
		if (!$regex->match()) {
			throw new G_Miner_Engine_BluePrint_Action_GetContents_Callback_Exception('regex did not match anything');
		}
		$fimlNum = $regex->getMatches(1);
		$fimlNum = (integer) $fimlNum;
		$fimlNum++;
		$url->setPath('film' . (string) $fimlNum . '.html');
		return $url->toString();
	}
	
	/**
	 * 
	 * @param unknown_type $string
	 * @return unknown_type
	 */
	public function explode($string)
	{
		return explode(',', $string);
	}
}