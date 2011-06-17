<?php 

require_once dirname(__FILE__) . '/../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Model/Register.php';


/**
 * 
 * Data Mapper for Register model 
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_RegisterMapper extends EP_Mapper 
{
/*	
	function fetchAllBetweenForExport( $startTime, $endTime, $fields = array( '*' ) )
	{
		$sql = "SELECT " . implode( ',', $fields );
		$sql = " FROM register WHERE regdate >= ? and regdate <= ? ";
		$sql .= " AND noexport = 0 AND auth = 1 ";
			
		$db = EP_Util_Database::pdo_connect();
		$sth = $db->prepare( $sql );
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Register' );
		}	
				
		$sth->execute( array( $id ));
		return $sth->fetch();	
	}
*/	
	
	function fetchAllByUid( $uid, $fields = array('*') )
	{
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM register ";
		$sql .= "WHERE uid = ? ";
		
		$db = EP_Util_Database::pdo_connect();
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Register' );
		}

		$sth->execute( array( $uid ));
		return $sth->fetchAll();
	}
	
	function fetch( $id, $fields = array('*') )
	{
		$db = EP_Util_Database::pdo_connect();
		
		$sql = "SELECT " . implode( ',', $fields );
		$sql .= " FROM register ";
		$sql .= "WHERE id = ? ";
		
		$sth = $db->prepare( $sql );
		
		if ( $this->getUsePublicVars() == true )
		{
			$sth->setFetchMode( PDO::FETCH_OBJ );
		}
		else 
		{
			$sth->setFetchMode( PDO::FETCH_CLASS, 'EP_Model_Register' );
		}

		$sth->execute( array( $id ));
		return $sth->fetch();
	}
	
	
	/**
	 * 
	 * Save the record to the db
	 * @param object $call
	 */
	public function save( $obj )
	{	
		$type = 'insert';
		
		if ( $obj->hasMessages() )
		{
			return false;
		}
		
		$db = EP_Util_Database::pdo_connect();
		
		if ( $obj->getId() > 0 )
		{
			// update
			$type = 'update';
			
			$sql = 'UPDATE register SET ';
		    $sql .= "regdate=:regdate, ";
		    $sql .= "uid=:uid, ";
		    $sql .= "sequence=:sequence, ";
		    $sql .= "vendorid=:vendorid, ";
		    $sql .= "apptype=:apptype, ";
		    $sql .= "first_name=:first_name, ";
		    $sql .= "mid_init=:mid_init, ";
		    $sql .= "last_name=:last_name, ";
		    $sql .= "suffix=:suffix, ";
		    $sql .= "vip=:vip, ";
		    $sql .= "busname=:busname, ";
		    $sql .= "revclass=:revclass, ";
		    $sql .= "servicephone=:servicephone, ";
		    $sql .= "sericeext=:sericeext, ";
		    $sql .= "addr1=:addr1, ";
		    $sql .= "addr2=:addr2, ";
		    $sql .= "addr3=:addr3, ";
		    $sql .= "city=:city, ";
		    $sql .= "state=:state, ";
		    $sql .= "zip5=:zip5, ";
		    $sql .= "zip4=:zip4, ";
		    $sql .= "county=:county, ";
		    $sql .= "billphone=:billphone, ";
		    $sql .= "billext=:billext, ";
		    $sql .= "baddr1=:baddr1, ";
		    $sql .= "baddr2=:baddr2, ";
		    $sql .= "baddr3=:baddr3, ";
		    $sql .= "bcity=:bcity, ";
		    $sql .= "bstate=:bstate, ";
		    $sql .= "bzip5=:bzip5, ";
		    $sql .= "bzip4=:bzip4, ";
		    $sql .= "bcounty=:bcounty, ";
		    $sql .= "att=:att, ";
		    $sql .= "email=:email, ";
		    $sql .= "servicetype=:servicetype, ";
		    $sql .= "territory_code=:territory_code, ";
		    $sql .= "marketer=:marketer, ";
		    $sql .= "iso=:iso, ";
		    $sql .= "taxex=:taxex, ";
		    $sql .= "billmeth=:billmeth, ";
		    $sql .= "entype=:entype, ";
		    $sql .= "today=:today, ";
		    $sql .= "distrib=:distrib, ";
		    $sql .= "account=:account, ";
		   	$sql .= "rateclass=:rateclass, ";
		    $sql .= "promocode=:promocode, ";
		    $sql .= "priceplan=:priceplan, ";
		    $sql .= "bdate=:bdate, ";
		    $sql .= "edate=:edate, ";
		    $sql .= "edate_plan=:edate_plan, ";
		    $sql .= "repid=:repid, ";
		    $sql .= "vas=:vas, ";
		    $sql .= "campaign=:campaign, ";
		    $sql .= "cellcode=:cellcode, ";
		    $sql .= "saledate=:saledate, ";
		    $sql .= "partnercode=:partnercode, ";
		    $sql .= "partner_memnum=:partner_memnum, ";
		    $sql .= "memlevel=:memlevel, ";
		    $sql .= "auth=:auth, ";
		    $sql .= "accept=:accept, ";
		    $sql .= "confcode=:confcode, ";
		    $sql .= "nowtime=:nowtime, ";
		    $sql .= "hpsemail=:hpsemail, ";
		    $sql .= "origurl=:origurl, ";
		    $sql .= "greenopt=:greenopt, ";	    
		   	$sql .= "refid=:refid, ";
		    $sql .= "sourceip=:sourceip, ";
		    $sql .= "busres=:busres, ";
		    $sql .= "budget=:budget, ";
		    $sql .= "entby=:entby, ";
		    $sql .= "appby=:appby, ";
		    $sql .= "namekey=:namekey, ";
		    $sql .= "baccount=:baccount, ";
		    $sql .= "introgroup=:introgroup, ";
		    $sql .= "mkgroup=:mkgroup, ";
		    $sql .= "stateid=:stateid, ";
		    $sql .= "pfname=:pfname, ";
		    $sql .= "plname=:plname, ";
		    $sql .= "enrollcustid=:enrollcustid, ";
		    $sql .= "fico=:fico, ";
		    $sql .= "paysrc=:paysrc, ";
		    $sql .= "paymeth=:paymeth, ";
		    $sql .= "payamt=:payamt, ";
		    $sql .= "contractterm=:contractterm, ";
		    $sql .= "spanishbill=:spanishbill, ";
		    $sql .= "notificationwaiver=:notificationwaiver, ";
		    $sql .= "dob=:dob, ";
		    $sql .= "mothermaiden=:mothermaiden, ";
		    $sql .= "taxid=:taxid, ";
		    $sql .= "credit1=:credit1, ";
		    $sql .= "credit2=:credit2, ";
		    $sql .= "kwh=:kwh, ";
		    $sql .= "rentown=:rentown, ";
		    $sql .= "residencelength=:residencelength, ";
		    $sql .= "employeecount=:employeecount, ";
		    $sql .= "businesslength=:businesslength, ";
		    $sql .= "currentsupplier=:currentsupplier, ";
		    $sql .= "spfname=:spfname, ";
		    $sql .= "splname=:splname, ";
		    $sql .= "prodcode=:prodcode, ";
		    $sql .= "fixedintro=:fixedintro, ";
		    $sql .= "noexport=:noexport, ";
		    $sql .= "years_inbiz=:years_inbiz, ";
		    $sql .= "years_bizaddr=:years_bizaddr, ";
		    $sql .= "late_payment6=:late_payment6, ";
		    $sql .= "busname_change=:busname_change, ";
		    $sql .= "elec_supp_prevyear=:elec_supp_prevyear, ";
		    $sql .= "years_creditbiz=:years_creditbiz ";				
			$sql .= 'WHERE id = :id ';
			
		}
		else
		{
			// insert
			$type = 'insert';
			
			$sql = "INSERT INTO register ";
		    $sql .= "VALUES ( null, ";
		    $sql .= ":regdate, ";
		    $sql .= ":uid, ";
		    $sql .= ":sequence, ";
		    $sql .= ":vendorid, ";
		    $sql .= ":apptype, ";
		    $sql .= ":first_name, ";
		    $sql .= ":mid_init, ";
		    $sql .= ":last_name, ";
		    $sql .= ":suffix, ";
		    $sql .= ":vip, ";
		    $sql .= ":busname, ";
		    $sql .= ":revclass, ";
		    $sql .= ":servicephone, ";
		    $sql .= ":sericeext, ";
		    $sql .= ":addr1, ";
		    $sql .= ":addr2, ";
		    $sql .= ":addr3, ";
		    $sql .= ":city, ";
		    $sql .= ":state, ";
		    $sql .= ":zip5, ";
		    $sql .= ":zip4, ";
		    $sql .= ":county, ";
		    $sql .= ":billphone, ";
		    $sql .= ":billext, ";
		    $sql .= ":baddr1, ";
		    $sql .= ":baddr2, ";
		    $sql .= ":baddr3, ";
		    $sql .= ":bcity, ";
		    $sql .= ":bstate, ";
		    $sql .= ":bzip5, ";
		    $sql .= ":bzip4, ";
		    $sql .= ":bcounty, ";
		    $sql .= ":att, ";
		    $sql .= ":email, ";
		    $sql .= ":servicetype, ";
		    $sql .= ":territory_code, ";
		    $sql .= ":marketer, ";
		    $sql .= ":iso, ";
		    $sql .= ":taxex, ";
		    $sql .= ":billmeth, ";
		    $sql .= ":entype, ";
		    $sql .= ":today, ";
		    $sql .= ":distrib, ";
		    $sql .= ":account, ";
		   	$sql .= ":rateclass, ";
		    $sql .= ":promocode, ";
		    $sql .= ":priceplan, ";
		    $sql .= ":bdate, ";
		    $sql .= ":edate, ";
		    $sql .= ":edate_plan, ";
		    $sql .= ":repid, ";
		    $sql .= ":vas, ";
		    $sql .= ":campaign, ";
		    $sql .= ":cellcode, ";
		    $sql .= ":saledate, ";
		    $sql .= ":partnercode, ";
		    $sql .= ":partner_memnum, ";
		    $sql .= ":memlevel, ";
		    $sql .= ":auth, ";
		    $sql .= ":accept, ";
		    $sql .= ":confcode, ";
		    $sql .= ":nowtime, ";
		    $sql .= ":hpsemail, ";
		    $sql .= ":origurl, ";
		    $sql .= ":greenopt, ";	    
		   	$sql .= ":refid, ";
		    $sql .= ":sourceip, ";
		    $sql .= ":busres, ";
		    $sql .= ":budget, ";
		    $sql .= ":entby, ";
		    $sql .= ":appby, ";
		    $sql .= ":namekey, ";
		    $sql .= ":baccount, ";
		    $sql .= ":introgroup, ";
		    $sql .= ":mkgroup, ";
		    $sql .= ":stateid, ";
		    $sql .= ":pfname, ";
		    $sql .= ":plname, ";
		    $sql .= ":enrollcustid, ";
		    $sql .= ":fico, ";
		    $sql .= ":paysrc, ";
		    $sql .= ":paymeth, ";
		    $sql .= ":payamt, ";
		    $sql .= ":contractterm, ";
		    $sql .= ":spanishbill, ";
		    $sql .= ":notificationwaiver, ";
		    $sql .= ":dob, ";
		    $sql .= ":mothermaiden, ";
		    $sql .= ":taxid, ";
		    $sql .= ":credit1, ";
		    $sql .= ":credit2, ";
		    $sql .= ":kwh, ";
		    $sql .= ":rentown, ";
		    $sql .= ":residencelength, ";
		    $sql .= ":employeecount, ";
		    $sql .= ":businesslength, ";
		    $sql .= ":currentsupplier, ";
		    $sql .= ":spfname, ";
		    $sql .= ":splname, ";
		    $sql .= ":prodcode, ";
		    $sql .= ":fixedintro, ";
		    $sql .= ":noexport, ";
		    $sql .= ":years_inbiz, ";
		    $sql .= ":years_bizaddr, ";
		    $sql .= ":late_payment6, ";
		    $sql .= ":busname_change, ";
		    $sql .= ":elec_supp_prevyear, ";
		    $sql .= ":years_creditbiz ";	
		    $sql .= " ) ";   
		}
		
		
	   	$sth = $db->prepare( $sql );
// print_r( $sth->errorInfo() );
// print_r( $db->errorInfo() );

	   	if ( $type == 'update' )
	   	{
	   		$sth->bindParam( ':id', $obj->getId(), PDO::PARAM_INT);
	   	}
	   	
	    $sth->bindParam( ':regdate', $obj->getRegdate(), PDO::PARAM_INT);
	    $sth->bindParam( ':uid', $obj->getUid(), PDO::PARAM_STR, 32 );
	    $sth->bindParam( ':sequence', $obj->getSequence(), PDO::PARAM_STR, 3 );
	    $sth->bindParam( ':vendorid', $obj->getVendorid(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':apptype', $obj->getApptype(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':first_name', $obj->getFirstName(), PDO::PARAM_STR, 60 );
	    $sth->bindParam( ':mid_init', $obj->getMidInit(), PDO::PARAM_STR, 5 );
	    $sth->bindParam( ':last_name', $obj->getLastName(), PDO::PARAM_STR, 60 );
	    $sth->bindParam( ':suffix', $obj->getSuffix(), PDO::PARAM_STR, 5 );
	    $sth->bindParam( ':vip', $obj->getVip(), PDO::PARAM_STR, 3 );
	    $sth->bindParam( ':busname', $obj->getBusname(), PDO::PARAM_STR, 150 );
	    $sth->bindParam( ':revclass', $obj->getRevclass(), PDO::PARAM_STR, 5 );
	    $sth->bindParam( ':servicephone', $obj->getServicephone(), PDO::PARAM_STR, 32 ); 
	    $sth->bindParam( ':sericeext',$obj->getSericeext(), PDO::PARAM_STR, 5 );
	    $sth->bindParam( ':addr1', $obj->getAddr1(), PDO::PARAM_STR, 64 );
	    $sth->bindParam( ':addr2', $obj->getAddr2(), PDO::PARAM_STR, 64 );
	    $sth->bindParam( ':addr3', $obj->getAddr3(), PDO::PARAM_STR, 64 );
	    $sth->bindParam( ':city', $obj->getCity(), PDO::PARAM_STR, 20 );
	    $sth->bindParam( ':state', $obj->getState(), PDO::PARAM_STR, 20 );
	    $sth->bindParam( ':zip5', $obj->getZip5(), PDO::PARAM_STR, 5 );
	    $sth->bindParam( ':zip4', $obj->getZip4(), PDO::PARAM_STR, 4 );
	    $sth->bindParam( ':county', $obj->getCounty(), PDO::PARAM_STR, 25 );
	    $sth->bindParam( ':billphone', $obj->getBillphone(), PDO::PARAM_STR, 32 );
	    $sth->bindParam( ':billext', $obj->getBillext(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':baddr1', $obj->getBaddr1(), PDO::PARAM_STR, 64 );
	    $sth->bindParam( ':baddr2', $obj->getBaddr2(), PDO::PARAM_STR, 64 );
	    $sth->bindParam( ':baddr3', $obj->getBaddr3(), PDO::PARAM_STR, 64 );
	    $sth->bindParam( ':bcity', $obj->getBcity(), PDO::PARAM_STR, 20 );
	    $sth->bindParam( ':bstate', $obj->getBstate(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':bzip5', $obj->getBzip5(), PDO::PARAM_STR, 5 );
	    $sth->bindParam( ':bzip4', $obj->getBzip4(), PDO::PARAM_STR, 4 );
	    $sth->bindParam( ':bcounty', $obj->getBcounty(), PDO::PARAM_STR, 25 );
	    $sth->bindParam( ':att',$obj->getAtt(), PDO::PARAM_STR, 100 );
	    $sth->bindParam( ':email', $obj->getEmail(), PDO::PARAM_STR, 150 );
	    $sth->bindParam( ':servicetype', $obj->getServicetype(), PDO::PARAM_INT );
	    $sth->bindParam( ':territory_code', $obj->getTerritoryCode(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':marketer', $obj->getMarketer(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':iso', $obj->getIso(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':taxex', $obj->getTaxex(), PDO::PARAM_INT );
	    $sth->bindParam( ':billmeth', $obj->getBillmeth(), PDO::PARAM_STR, 5 );
	    $sth->bindParam( ':entype', $obj->getEntype(), PDO::PARAM_INT );
	    $sth->bindParam( ':today', $obj->getToday(), PDO::PARAM_STR, 10 );
		$sth->bindParam( ':distrib', $obj->getDistrib(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':account', $obj->getAccount(), PDO::PARAM_STR, 30 );
	    $sth->bindParam( ':rateclass', $obj->getRateclass(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':promocode', $obj->getPromocode(), PDO::PARAM_STR, 5 );
	    $sth->bindParam( ':priceplan', $obj->getPriceplan(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':bdate', $obj->getBdate(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':edate', $obj->getEdate(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':edate_plan', $obj->getEdatePlan(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':repid', $obj->getRepid(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':vas', $obj->getVas(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':campaign', $obj->getCampaign(), PDO::PARAM_STR, 5 );
	    $sth->bindParam( ':cellcode', $obj->getCellcode(), PDO::PARAM_STR, 5 );
		$sth->bindParam( ':saledate', $obj->getSaledate(), PDO::PARAM_INT );
	    $sth->bindParam( ':partnercode', $obj->getPartnercode(), PDO::PARAM_STR, 5 );
	    $sth->bindParam( ':partner_memnum', $obj->getPartnerMemnum(), PDO::PARAM_STR, 20 );
	    $sth->bindParam( ':memlevel', $obj->getMemlevel(), PDO::PARAM_STR, 15 );
	    $sth->bindParam( ':auth', $obj->getAuth(), PDO::PARAM_STR, 25 );
	    $sth->bindParam( ':accept', $obj->getAccept(), PDO::PARAM_INT );
	    $sth->bindParam( ':confcode', $obj->getConfcode(), PDO::PARAM_STR, 25 );
	    $sth->bindParam( ':nowtime', $obj->getNowtime(), PDO::PARAM_INT );
	    $sth->bindParam( ':hpsemail', $obj->getHpsemail(), PDO::PARAM_STR, 150 );
	    $sth->bindParam( ':origurl', $obj->getOrigurl(), PDO::PARAM_STR, 150 );
		$sth->bindParam( ':greenopt', $obj->getGreenopt(), PDO::PARAM_STR, 6 );  
	    $sth->bindParam( ':refid', $obj->getRefid(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':sourceip', $obj->getSourceip(), PDO::PARAM_STR, 32 );
	    $sth->bindParam( ':busres', $obj->getBusres(), PDO::PARAM_INT );
	    $sth->bindParam( ':budget', $obj->getBudget(), PDO::PARAM_INT );
	    $sth->bindParam( ':entby', $obj->getEntby(), PDO::PARAM_STR, 5 );
	    $sth->bindParam( ':appby', $obj->getAppby(), PDO::PARAM_STR, 5 );
	    $sth->bindParam( ':namekey', $obj->getNamekey(), PDO::PARAM_STR, 32 );
	    $sth->bindParam( ':baccount', $obj->getBaccount(), PDO::PARAM_STR, 32 );
	    $sth->bindParam( ':introgroup', $obj->getIntrogroup(), PDO::PARAM_STR, 32 );
		$sth->bindParam( ':mkgroup', $obj->getMkgroup(), PDO::PARAM_STR, 32 );
	    $sth->bindParam( ':stateid', $obj->getStateid(), PDO::PARAM_INT );  
	    $sth->bindParam( ':pfname', $obj->getPfname(), PDO::PARAM_STR, 20 );
	    $sth->bindParam( ':plname', $obj->getPlname(), PDO::PARAM_STR, 20 );
	    $sth->bindParam( ':enrollcustid', $obj->getEnrollcustid(), PDO::PARAM_STR, 20 );
	    $sth->bindParam( ':fico', $obj->getFico(), PDO::PARAM_STR, 20 );
	    $sth->bindParam( ':paysrc', $obj->getPaysrc(), PDO::PARAM_STR, 20 );
	    $sth->bindParam( ':paymeth', $obj->getPaymeth(), PDO::PARAM_STR, 20 );
	    $sth->bindParam( ':payamt', $obj->getPayamt() );
	    $sth->bindParam( ':contractterm', $obj->getContractterm(), PDO::PARAM_STR, 50 );
	    $sth->bindParam( ':spanishbill', $obj->getSpanishbill(), PDO::PARAM_STR, 5 );
	    $sth->bindParam( ':notificationwaiver', $obj->getNotificationwaiver(), PDO::PARAM_STR, 5 );
		$sth->bindParam( ':dob', $obj->getDob(), PDO::PARAM_STR, 32 );
	    $sth->bindParam( ':mothermaiden', $obj->getMothermaiden(), PDO::PARAM_STR, 24 );
	    $sth->bindParam( ':taxid', $obj->getTaxid(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':credit1', $obj->getCredit1(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':credit2', $obj->getCredit2(), PDO::PARAM_STR, 30 );
	    $sth->bindParam( ':kwh', $obj->getKwh(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':rentown', $obj->getRentOwn(), PDO::PARAM_STR, 2 );
	    $sth->bindParam( ':residencelength', $obj->getResidenceLength(), PDO::PARAM_INT );
	    $sth->bindParam( ':employeecount', $obj->getEmployeeCount(), PDO::PARAM_INT );
	    $sth->bindParam( ':businesslength', $obj->getBusinessLength(), PDO::PARAM_INT );
	    $sth->bindParam( ':currentsupplier', $obj->getCurrentSupplier(), PDO::PARAM_INT );
	    $sth->bindParam( ':spfname', $obj->getSpfname(), PDO::PARAM_STR, 50 );
	    $sth->bindParam( ':splname', $obj->getSplname(), PDO::PARAM_STR, 50 );
	    $sth->bindParam( ':prodcode', $obj->getProdCode(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':fixedintro', $obj->getFixedIntro(), PDO::PARAM_STR, 10 );
	    $sth->bindParam( ':noexport', $obj->getNoexport(), PDO::PARAM_INT );
	    $sth->bindParam( ':years_inbiz', $obj->getYearsInbiz(), PDO::PARAM_STR, 60 );
	    $sth->bindParam( ':years_bizaddr', $obj->getYearsBizaddr(), PDO::PARAM_STR, 60 );
	    $sth->bindParam( ':late_payment6', $obj->getLatePayment6(), PDO::PARAM_STR, 60 );
	    $sth->bindParam( ':busname_change', $obj->getBusnameChange(), PDO::PARAM_STR, 60 );
	    $sth->bindParam( ':elec_supp_prevyear', $obj->getElecSuppPrevyear(), PDO::PARAM_STR, 60 );
	    $sth->bindParam( ':years_creditbiz', $obj->getYearsCreditbiz(), PDO::PARAM_STR, 60 );
		    
		$result = $sth->execute();

		if ( $result == 1 )
		{
			if ( $type == 'insert' )
			{
				$obj->setId( (int)$db->lastInsertId() );
			}
			return true;
		}    
		return false;
	}

	public function generateConfcode()
	{
		$dup = 1; // assume duplicate confcode
		$db = $this->getDatabaseConnection();
		$sql = "SELECT EXISTS (SELECT 1 FROM register WHERE confcode = ?)";
		$sth = $db->prepare($sql);
		while($dup)
		{
			$confcode = $uid = substr(uniqid('Z'),-7,7);
			$sth->execute(array($confcode));
			$dup = $sth->fetchColumn(0);
			$sth->closeCursor();
		}
		return $confcode;
	}
}




