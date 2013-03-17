<?php
/**
 * This interface is used in G_Engine
 * 
 * @author gui
 *
 */
interface G_Db_Buttons_Interface
{	
	/**
	 * Whenever there is data to update new fields to insert
	 * call this.
	 * 
	 * Important it needs a reference;
	 * 
	 * @param G_Object_Abstract $instance
	 * @return unknown_type
	 */
	public function save();
	
	/**
	 * Delete what is in database
	 * 
	 * @param G_Object_Abstract $instance
	 * @return unknown_type
	 */
	public function delete();
	
	/**
	 * Gets or generates an id from the bare minimum info
	 * 
	 * @param G_Object_IdElement $instance
	 * @return unknown_type
	 */
	public function getId();
}