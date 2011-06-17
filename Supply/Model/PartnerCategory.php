<?php
require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';

/**
 * PartnerCategory model, for partner_categories table
 */

class EP_Model_PartnerCategory extends EP_Model
{
	protected $id;
	protected $name;
	protected $date_created;

	/**
	   @return int id of category
	*/
	public function getId()
	{
		return $this->id;
	}

	/**
	   @param int $id id of category
	*/
	public function setId($id)
	{
		$this->id = $id;
	}
	
	/**
	   @return string name of category
	*/
	public function getName()
	{
		return $this->name;
	}

	/**
	   @param string $name name of category
	*/
	public function setName($name)
	{
		$this->name = $name;
	}
	
	/**
	   @return string date category was created
	*/
	public function getDateCreated()
	{
		return $this->date_created;
	}

	/**
	   @param string $date_created date category was created
	*/
	public function setDateCreated($date_created)
	{
		$this->date_created = $date_created;
	}
}