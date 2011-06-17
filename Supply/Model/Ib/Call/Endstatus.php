<?php 

require_once dirname(__FILE__) . '/../../../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Endstatus model ...
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Ib_Call_Endstatus extends EP_Model
{
	protected $id;
	protected $name;
	protected $date_created;
	
	/**
	 * 
	 * @param int $id
	 */
	public function setId( $id )
	{
		$this->id = $id;
		return $this;
	}
	
	/**
	 * 
	 * @return int 
	 */ 	 
	public function getId()
	{
		return (int)$this->id;
	}
	
	/**
	 * 
	 * @param int $name
	 */
	public function setName( $name )
	{
		$this->name = $name;
		return $this;
	}
	
	/**
	 * 
	 * @return str 
	 */ 	 
	public function getName()
	{
		return $this->name;
	}
	
	
	/**
	 * @return the $date_created
	 */
	public function getDateCreated() {
		return $this->date_created;
	}

	/**
	 * @param field_type $date_created
	 */
	public function setDateCreated($date_created) {
		$this->date_created = $date_created;
		return $this;
	}
}