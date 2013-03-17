<?php
/**
 * This plugin uses the G_Db_Req registered
 * instance.
 * 
 * So make sure an instance is registered.
 * 
 * @author gui
 *
 */
class G_Zend_Plugin_Cache_Updater
extends Zend_Controller_Plugin_Abstract
{

	/**
	 * 
	 * @param Zend_Controller_Request_Abstract $request
	 * @return unknown_type
	 */
	public function responseSent()
	{
		//if the time is greater or equal to the last update + 1 day
		//if (G_Time::UNIXTS_DAY) {
			//update again
			$z = new G_VLs_Req_Admin();
			//$z->updateCatsWithThumbsTable();
		//}
	}
}