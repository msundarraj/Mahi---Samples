<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Partner model ...
 * @author Siva Aynkaran and Matthew Flaschen
 *
 */
class EP_Model_RateClass extends EP_Model
{
	protected $group;
       protected $description;
       protected $distrib;
     
	/**
	 * @return the $group
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * @param field_type $description
	 */
	public function setGroup($group) {
		$this->group = $group;
	}

       /**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param field_type $group
	 */
	public function setDescription($description) {
		$this->description= $description;
	}

	/**
	 * @return $distrib, a foreign key to utility2.code
	 */
	public function getDistrib() {
		return $this->distrib;
	}

	/**
	 * @param $distrib a foreign key to utility2.code
	 */
	public function setDistrib($distrib) {
		$this->distrib = $distrib;
	}
}


