<?php 

require_once dirname(__FILE__) . '/../../../../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Category model ...
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Ib_Call_Dispo_Category extends EP_Model
{
	protected $id;
	protected $name;
	protected $sort;
	protected $date_created;
	protected $date_mod;
	
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
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param field_type $name
	 */
	public function setName($name) {
		if ( strlen( $name ) > 45 )
		{
			$this->setMessage( 'ERROR', 'name must be between 0 and 45 chars' );
			return false;
		}
		$this->name = $name;
	}

	/**
	 * @return the $sort
	 */
	public function getSort() {
		return $this->sort;
	}

	/**
	 * @param field_type $sort
	 */
	public function setSort($sort) {
		$this->sort = $sort;
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

	/**
	 * @return the $date_mod
	 */
	public function getDateMod() {
		return $this->date_mod;
	}

	/**
	 * @param field_type $date_mod
	 */
	public function setDateMod($date_mod) {
		$this->date_mod = $date_mod;
		return $this;
	}
}



