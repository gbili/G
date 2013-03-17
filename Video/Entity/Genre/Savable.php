<?php
/**
 * Title and slug
 * 
 * @author gui
 *
 */
class G_Video_Entity_Genre_Savable
extends G_ValueSlug_Savable
{
	public function __construct($title)
	{
		parent::__construct($title);
		$this->setCustomRequestorTableName('Genre');
	}
}