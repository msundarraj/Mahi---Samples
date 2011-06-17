<?php 

require_once dirname(__FILE__) . '/../../../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Disposition model ...
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Ib_Call_Dispo extends EP_Model
{
	protected $id;
	protected $name;
	protected $category_id;
	protected $count_against_net_conversion;
	protected $auto_dispo;
	protected $allow_comments;
	protected $comments_prompt;
	protected $has_enhancements;
	protected $date_created;
	protected $date_mod;
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return (int)$this->id;
	}

	/**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id = $id;
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
		$this->name = $name;
	}

	/**
	 * @return the $category_id
	 */
	public function getCategoryId() {
		return $this->category_id;
	}

	/**
	 * @param field_type $category_id
	 */
	public function setCategoryId($category_id) {
		$this->category_id = $category_id;
	}

	/**
	 * @return the $count_against_net_conversion
	 */
	public function getCountAgainstNetConversion() {
		return $this->count_against_net_conversion;
	}

	/**
	 * @param field_type $count_against_net_conversion
	 */
	public function setCountAgainstNetConversion($count_against_net_conversion) {
		$this->count_against_net_conversion = $count_against_net_conversion;
	}

	/**
	 * @return the $auto_dispo
	 */
	public function getAutoDispo() {
		return $this->auto_dispo;
	}

	/**
	 * @param field_type $auto_dispo
	 */
	public function setAutoDispo($auto_dispo) {
		$this->auto_dispo = $auto_dispo;
	}

	/**
	 * @return the $allow_comments
	 */
	public function getAllowComments() {
		return $this->allow_comments;
	}

	/**
	 * @param field_type $allow_comments
	 */
	public function setAllowComments($allow_comments) {
		$this->allow_comments = $allow_comments;
	}

	/**
	 * @return the $comments_prompt
	 */
	public function getCommentsPrompt() {
		return $this->comments_prompt;
	}

	/**
	 * @param field_type $comments_prompt
	 */
	public function setCommentsPrompt($comments_prompt) {
		$this->comments_prompt = $comments_prompt;
	}

	/**
	 * @return the $has_enhancements
	 */
	public function getHasEnhancements() {
		return $this->has_enhancements;
	}

	/**
	 * @param field_type $has_enhancements
	 */
	public function setHasEnhancements($has_enhancements) {
		$this->has_enhancements = $has_enhancements;
		
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
	}

	
	
}