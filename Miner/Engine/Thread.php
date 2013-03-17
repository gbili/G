<?php
/**
 * This class will hold the current
 * action being executed and the
 * code returned by the function 
 * that is executing it.
 * 
 * @author gui
 *
 */
class G_Miner_Engine_Thread
{
	/**
	 * The action being executed
	 * 
	 * @var G_Miner_Engine_BluePrint_Action_Abstract
	 */
	private $_action = null;
	
	/**
	 * Contains execution status code
	 * (ACTION_EXECUTION_SUCCEED|FAIL etc.)
	 * 
	 * @var integer
	 */
	private $_code = null;
	
	/**
	 * 
	 * @param G_Miner_Engine_BluePrint_Action_Abstract $action
	 * @param unknown_type $code
	 * @return unknown_type
	 */
	public function __construct(G_Miner_Engine_BluePrint_Action_Abstract $action = null, $statusCode = null)
	{
		if (null !== $action) {
			$this->_action = $action;
		}

		if (null !== $statusCode) {
			$this->_code = $statusCode;
		}
	}
	
	/**
	 * 
	 * @return G_Miner_Engine_BluePrint_Action_Abstract
	 */
	public function getAction()
	{
		if (null === $this->_action) {
			throw new G_Miner_Engine_Thread_Exception("The action is not set");
		}
		return $this->_action;
	}
	
	/**
	 * 
	 * @param G_Miner_Engine_BluePrint_Action_Abstract $action
	 * @return void
	 */
	public function setAction(G_Miner_Engine_BluePrint_Action_Abstract $action)
	{
		$this->_action = $action;
	}
	
	/**
	 * 
	 * @return integer
	 */
	public function getStatus()
	{
		if (null === $this->_code) {
			throw new G_Miner_Engine_Thread_Exception("The code is not set");
		}
		return $this->_code;
	}
	
	/**
	 * 
	 * @param integer $code
	 * @return void
	 */
	public  function setStatus($code)
	{
		$this->_code = $code;
	}
}