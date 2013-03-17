<?php
/**
 * Extend the parent so there will be no need to specify
 * the different requestor prefixed adapter for every
 * subclass
 * 
 * @author gui
 *
 */
class G_Vid_ValueSlug_Savable
extends G_ValueSlug_Savable
{
	public function __construct($value)
	{
		parent::__construct($value);
		$this->setDifferentRequestorPrefixedAdapter('G_Vid');
	}
}