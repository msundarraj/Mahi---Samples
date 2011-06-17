<?php
require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Model.php';

class EP_Model_StarburstVariables extends EP_Model
{
    protected $id;
    protected $partner_id;
    protected $promo_trigger;
    protected $image_miles;
    protected $text1_miles;
    protected $text2_miles;
    protected $imagepath;
    protected $bullet_snippet;
    protected $bullet_snippet2;
    protected $disc_sentence;
    protected $resaffin;
    protected $bizaffin;
    protected $aff_biz_ongoing;
    protected $aff_res_ongoing;
    protected $footnote;
    protected $ib_desc;
    protected $bonus_mon;
    protected $new;
    protected $is_gas;

    /**
       @return id, as int
    */
    public function getId()
    {
	return $this->id;
    }

    /**
      @param $id id, as int
    */
    public function setId($id)
    {
	$this->id = $id;
    }

    /**
       @return partner id, as int
    */
    public function getPartnerId()
    {
	return $this->partner_id;
    }

    /**
       @param $partner_id partner id, as int
    */
    public function setPartnerId($partner_id)
    {
	$this->partner_id = $partner_id;
    }

    /**
       @return promo trigger, as string
    */
    public function getPromoTrigger()
    {
	return $this->promo_trigger;
    }

    /**
       @param $promo_trigger promo trigger, as string
    */
    public function setPromoTrigger($promo_trigger)
    {
	$this->promo_trigger = $promo_trigger;
    }

    /**
       @return reward image, as string (unused)
    */
    public function getImageMiles()
    {
	return $this->image_miles;
    }

    /**
       @param reward image, as string (unused)
    */
    public function setImageMiles($image_miles)
    {
	$this->image_miles = $image_miles;
    }

    /**
       @return first reward text, as string
    */
    public function getText1Miles()
    {
	return $this->text1_miles;
    }

    /**
       @param $text1_miles
    */
    public function setText1Miles($text1_miles)
    {
	$this->text1_miles = $text1_miles;
    }

    /**
       @return second reward text, as string
    */
    public function getText2Miles()
    {
	return $this->text2_miles;
    }

    /**
       @param $text2_miles second reward text, as string
    */
    public function setText2Miles($text2_miles)
    {
	$this->text2_miles = $text2_miles;
    }

    /**
       @return path to reward image, as string
    */
    public function getImagePath()
    {
	return $this->imagepath;
    }

    /**
       @param $imagepath path to reward image, as string
    */
    public function setImagePath($imagepath)
    {
	$this->imagepath = $imagepath;
    }

    /**
       @return first reward bullet text, as string
    */
    public function getBulletSnippet()
    {
	return $this->bullet_snippet;
    }

    /**
       @param $bullet_snippet first reward bullet text, as string
    */
    public function setBulletSnippet($bullet_snippet)
    {
	$this->bullet_snippet = $bullet_snippet;
    }

    /**
       @return second reward bullet text, as string
    */
    public function getBulletSnippet2()
    {
	return $this->bullet_snippet2;
    }

    /**
       @param $bullet_snippet2 second reward bullet text, as string
    */
    public function setBulletSnippet2($bullet_snippet2)
    {
	$this->bullet_snippet2 = $bullet_snippet2;
    }

    /**
       @return disc sentence, as string
    */
    public function getDiscSentence()
    {
	return $this->disc_sentence;
    }

    /**
       @param $disc_sentence disc sentence, as string
    */
    public function setDiscSentence($disc_sentence)
    {
	$this->disc_sentence = $disc_sentence;
    }

    /**
       @return residential affinity, as string
    */
    public function getResAffin()
    {
	return $this->resaffin;
    }

    /**
       @param $resaffin residential affinity, as string
    */
    public function setResAffin($resaffin)
    {
	$this->resaffin = $resaffin;
    }
    
    /**
       @return business affinity, as string
    */
    public function getBizAffin()
    {
	return $this->bizaffin;
    }

    /**
       @param $bizaffin business affinity, as string
    */
    public function setBizAffin($bizaffin)
    {
	$this->bizaffin = $bizaffin;
    }

    /**
       @return ongoing affinity business reward, as string
    */
    public function getAffBizOngoing()
    {
	return $this->aff_biz_ongoing;
    }

    /**
       @param $aff_biz_ongoing ongoing affinity business reward, as string
    */
    public function setAffBizOngoing($aff_biz_ongoing)
    {
	$this->aff_biz_ongoing = $aff_biz_ongoing;
    }

    /**
       @return ongoing affinity residential reward, as string
    */
    public function getAffResOngoing()
    {
	return $this->aff_res_ongoing;
    }
    
    /**
       @param $aff_res_ongoing ongoing affinity residential reward, as string
    */
    public function setAffResOngoing($aff_res_ongoing)
    {
	$this->aff_res_ongoing = $aff_res_ongoing;
    }

    /**
       @return footnote, as string
    */
    public function getFootnote()
    {
	return $this->footnote;
    }

    /**
       @param $footnote footnote, as string
    */
    public function setFootnote($footnote)
    {
	$this->footnote = $footnote;
    }

    /**
       @return inbound description, as string
    */
    public function getIbDesc()
    {
	return $this->ib_desc;
    }

    /**
       @param $ib_desc inbound description, as string
    */
    public function setIbDesc($ib_desc)
    {
	$this->ib_desc = $ib_desc;
    }

    /**
       @return text of ordinal number of bonus month, as string
    */
    public function getBonusMon()
    {
	return $this->bonus_mon;
    }

    /**
       @param $bonus_mon text of ordinal number of bonus month, as string
    */
    public function setBonusMon($bonus_mon)
    {
	$this->bonus_mon = $bonus_mon;
    }

    /**
       @return new flag, as int
    */
    public function getNew()
    {
	return $this->new;
    }

    /**
       @param $new new flag, as int
    */
    public function setNew($new)
    {
	$this->new = $new;
    }

    /**
       @return gas flag, true for gas, false otherwise
    */
    public function getIsGas()
    {
	return $this->is_gas;
    }

    /**
       @param $is_gas gas flag, true for gas, false otherwise
    */
    public function setIsGas($is_gas)
    {
	$this->is_gas = $is_gas;
    }
}