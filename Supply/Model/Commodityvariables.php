<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';

/**
 * 
 * Commodityvariables model ...
 * @author SAynkaran
 *
 */
class EP_Model_Commodityvariables extends EP_Model
{
	protected $id;
	protected $name;
	protected $type;
	protected $bullet;
	protected $note;
	protected $affinbonus;
	protected $affinongoing;
	protected $BRD;
	protected $ongoing;
	protected $app_account;
	protected $dollar;
	protected $bonus;
	protected $promo;
       protected $snippet;
	protected $month;
	protected $fixedIntro;
	protected $disclosure;
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id= $id;
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
		$this->name= $name;
	}

	/**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param field_type $type
	 */
	public function setType($type) {
		$this->type= $type;
	}

	/**
	 * @return the $bullet
	 */
	public function getBullet() {
		return $this->bullet;
	}

	/**
	 * @param field_type $bullet
	 */
	public function setBullet($bullet) {
		$this->bullet= $bullet;
	}

	/**
	 * @return the $note
	 */
	public function getNote() {
		return $this->note;
	}

	/**
	 * @param field_type $note
	 */
	public function setNote($note) {
		$this->note= $note;
	}

	/**
	 * @return the $affinbonus
	 */
	public function getAffinbonus() {
		return $this->affinbonus;
	}

	/**
	 * @param field_type $affinbonus
	 */
	public function setAffinbonus($affinbonus) {
		$this->affinbonus= $affinbonus;
	}

	/**
	 * @return the $affinongoing
	 */
	public function getAffinongoing() {
		return $this->affinongoing;
	}

	/**
	 * @param field_type $affinongoing
	 */
	public function setAffinongoing($affinongoing) {
		$this->affinongoing= $affinongoing;
	}

	/**
	 * @return the $BRD
	 */
	public function getBRD() {
		return $this->BRD;
	}

	/**
	 * @param field_type $BRD
	 */
	public function setBRD($BRD) {
		$this->BRD= $BRD;
	}

	/**
	 * @return the $ongoing
	 */
	public function getOngoing() {
		return $this->ongoing;
	}

	/**
	 * @param field_type $ongoing
	 */
	public function setOngoing($ongoing) {
		$this->ongoing= $ongoing;
	}

	/**
	 * @return the $app_account
	 */
	public function getApp_account() {
		return $this->app_account;
	}

	/**
	 * @param field_type $app_account
	 */
	public function setApp_account($app_account) {
		$this->app_account= $app_account;
	}

	/**
	 * @return the $dollar
	 */
	public function getDollar() {
		return $this->dollar;
	}

	/**
	 * @param field_type $dollar
	 */
	public function setDollar($dollar) {
		$this->dollar= $dollar;
	}

	/**
	 * @return the $bonus
	 */
	public function getBonus() {
		return $this->bonus;
	}

	/**
	 * @param field_type $bonus
	 */
	public function setBonus($bonus) {
		$this->bonus= $bonus;
	}

	/**
	 * @return the $promo
	 */
	public function getPromo() {
		return $this->promo;
	}

	/**
	 * @param field_type $promo
	 */
	public function setPromo($promo) {
		$this->promo= $promo;
	}

      /**
	 * @return the $snippet
	 */
	public function getSnippet() {
		return $this->snippet;
	}

	/**
	 * @param field_type $snippet
	 */
	public function setSnippet($snippet) {
		$this->snippet= $snippet;
	}

        /**
	 * @return the $month
	 */
	public function getMonth() {
		return $this->month;
	}

	/**
	 * @param field_type $month
	 */
	public function setMonth($month) {
		$this->month= $month;
	}
   
        /**
	 * @return the $fixedIntro
	 */
	public function getFixedIntro() {
		return $this->fixedIntro;
	}

	/**
	 * @param field_type $fixedIntro
	 */
	public function setFixedIntro($fixedIntro) {
		$this->fixedIntro= $fixedIntro;
	}

         /**
	 * @return the $disclosure
	 */
	public function getDisclosure() {
		return $this->disclosure;
	}

	/**
	 * @param field_type $disclosure
	 */
	public function setDisclosure($disclosure) {
		$this->disclosure= $disclosure;
	}



}




