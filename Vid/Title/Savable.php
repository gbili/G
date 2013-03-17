<?php
/**
 * Title and slug
 * 
 * @author gui
 *
 */
class G_Vid_Title_Savable
extends G_Vid_ValueSlug_Savable
{
	public function __construct($title)
	{
		parent::__construct($title);
		$this->setPassTableNameToRequestor();
	}
}