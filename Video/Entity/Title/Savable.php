<?php
/**
 * Title and slug
 * 
 * @author gui
 *
 */
class G_Video_Entity_Title_Savable
extends G_ValueSlug_Savable
{
	public function __construct($title)
	{
		parent::__construct($title);
		$this->setCustomRequestorTableName('VideoEntity_Title');
	}
}