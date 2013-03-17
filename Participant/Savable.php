<?php
/**
 * This is the conjunction of 
 * 	-a MIE (which identifies a person)
 *  -a Video_Entity (which identifies a movie)
 *  -a involvement type (a role name)
 *  which results in a participant
 *  that means:
 *  the role of a MIE in a certain Movie
 * 
 * @author gui
 *
 */
class G_Participant_Savable
extends G_Molecule_Savable
{	
	
	/**
	 * 
	 * @param G_Participant_Role_Savable $role
	 * @param G_MIE_Savable $mIE
	 * @param G_Video_Entity_Savable $vE
	 * @return void
	 */
	public function __construct(G_Participant_Role_Savable $role,
								G_MIE_Savable $mIE,
								G_Video_Entity_SharedInfo_Savable $sH)
	{
		parent::__construct();
		$this->_setElement('role', $role);
		$this->_setElement('mIE', $mIE);
		$this->_setElement('sharedInfo', $sH);
	}

	/**
	 * 
	 * @return unknown_type
	 */
	public function getRole()
	{
		return $this->getElement('role');
	}
	
	/**
	 * Mie must be saved before participant can
	 * 
	 * @return unknown_type
	 */
	public function getMIE()
	{
		return $this->getElement('mIE');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getSharedInfo()
	{
		return $this->getElement('sharedInfo');
	}
}