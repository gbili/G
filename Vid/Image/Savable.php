<?php
class G_Vid_Image_Savable
extends G_Image_Savable
{
	public function __construct()
	{
		parent::__construct();
		//change requestor adapter
		$this->setDifferentRequestorPrefixedAdapter('G_Vid');
		//use parent requestor
		$this->setRequestorClassName('G_Image_Savable');
		//set different table name
		$this->setCustomRequestorTableName('G_Vid_Image');
	}
}