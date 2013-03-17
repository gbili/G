<?php
/**
 * An aggregation consists of a group of users
 * 
 * As in real life, with any group there must be
 * a set of rules that make the power model.
 * 
 * In unix, the power model is as follows:
 * there is a master: root, that has all the powers
 * and decides who has the right to do/access what.¡
 * 
 * @author Gui
 *
 */
class G_Aggr_Abstract
{
	/**
	 * 
	 * @var unknown_type
	 */
	const POWMOD_MONARCHY 	  = 1000; // democratic aggregation
	const POWMOD_TRUST		  = 1001;
	const POWMOD_OLIGARCHY 	  = 1002;
	const POWMOD_DEMOCRACY 	  = 1003;
	const POWMOD_SPECIAL_RULE = 1004;
	
	/**
	 * The user must
	 * @var unknown_type
	 */
	const AGGR_COUNTSRC_STATIC = 2000;
	const AGGR_COUNTSRC_DYNAMIC = 2001;
	
	/**
	 * 
	 * @var unknown_type
	 */
	const AGGR_INVCONFIR_TACIT = 3000;
	const AGGR_INVCONFIR_CONFIRM = 3001;
	
	/**
	 * 
	 * @var unknown_type
	 */
	const AGGR_INVPRIV_PRIVATE = 4000;
	const AGGR_INVPRIV_PUBLIC = 4001;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_id = null;
	
	private $_creatorUId = null;
	
	private $_users = null;
	
	private $_isOpen = null;
	
	private $_countSrc = null;
	
	private $_powMod = null;
	
	private $_invConfir = null;
	
	private $_invPriv = null;
	
	/**
	 * 
	 * @param int $aggrId
	 * @return unknown_type
	 */
	public function __construct ( $aggrId = null ){}
	
	/**
	 * 
	 * @param int $userId
	 * @return unknown_type
	 */
	public function setCreator ( $userId ) {}
	
	/**
	 * 
	 * @param int | array $userId
	 * @return unknown_type
	 */
	public function addUser ( $userId ) {}
	
	/**
	 * 
	 * @return bool
	 */
	public function isOpen () {}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function close () {}
	
	/**
	 * 
	 * @param int $userId
	 * @return invitationUnread | invitationRead | requestSent | requestAccepted
	 */
	public function getUserInvolvementStatus ( $userId ) {}
	
	/**
	 * 
	 * @param $const
	 * @return unknown_type
	 */
	public function setCountSrc ( $const ) {}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function save () {}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function delete () {}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getId () {}
	
	/**
	 * 
	 * @param const (1000 to 1004) $powmod
	 * @return unknown_type
	 */
	protected function _setPowmod ( $const ) {}
	
	/**
	 * Admin | team | competition | match
	 * @param $type
	 * @return unknown_type
	 */
	protected function _setType ( $type ) {}
	
	/**
	 * 
	 * @return unknown_type
	 */
	private function _isUserCountComplete () {}
	

}