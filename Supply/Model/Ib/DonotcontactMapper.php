<?php 

require_once dirname(__FILE__) . '/../../../../config.php';
require_once 'EP/Mapper.php';
require_once 'EP/Util/Database.php';

/**
 * 
 * Data Mapper for Donotcontact model 
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Ib_DonotcontactMapper extends EP_Mapper 
{
		
	/**
	 * 
	 * Save the record to the db
	 * @param object $call
	 */
	public function save( $obj )
	{	
		$date = date( 'Y-m-d H:i:s' );
		
		if ( $obj->getId() > 0 )
		{
			$type = 'update';
		}
		else
		{
			$type = 'insert';
		}
		
		if ( is_null( $obj->getDateCreated() ))
		{
			$obj->setDateCreated( $date );
		}
		
		if ( $obj->hasMessages() )
		{
			return false;
		}
		
		$db = $this->getDatabaseConnection();
			
		if ( $type == 'insert' )
		{
		    $sql = "INSERT INTO do_not_contacts (  first_name, mid_init, last_name, addr1, addr2, city, state, zip5, zip4, email, phone, date_created ) VALUES ( ";
		    //$sql .= ":call_id, ";
		    //$sql .= ":operator_id, ";
		    $sql .= ":first_name, ";
		    $sql .= ":mid_init, ";
		    $sql .= ":last_name, ";
		    $sql .= ":addr1, ";
		    $sql .= ":addr2, ";
		    $sql .= ":city, ";
		    $sql .= ":state, ";
		    $sql .= ":zip5, ";
		    $sql .= ":zip4, ";
		    $sql .= ":email, ";
		    $sql .= ":phone, ";
		    $sql .= ":date_created ";
		    $sql .= ")";
		}
		else 
		{
		    $sql = "UPDATE do_not_contacts ";
		    $sql .= "SET ";
		   // $sql .= "call_id=:call_id, ";
		   // $sql .= "operator_id=:operator_id, ";
		    $sql .= "first_name=:first_name, ";
		    $sql .= "mid_init=:mid_init, ";
		    $sql .= "last_name=:last_name, ";
		    $sql .= "addr1=:addr1, ";
		    $sql .= "addr2=:addr2, ";
		    $sql .= "city=:city, ";
		    $sql .= "state=:state, ";
		    $sql .= "zip5=:zip5, ";
		    $sql .= "zip4=:zip4, ";
		    $sql .= "email=:email, ";
		    $sql .= "phone=:phone, ";
		    $sql .= "date_created=:date_created ";
		    $sql .= "WHERE id = :id ";		
		}

	    $sth = $db->prepare( $sql );
// print_r( $db->errorInfo() );
// print_r( $sth->errorInfo() );
	   // $sth->bindParam(':call_id', $obj->getCallId(), PDO::PARAM_INT);
	   // $sth->bindParam(':operator_id', $obj->getOperatorId(), PDO::PARAM_INT);
		$sth->bindParam(':first_name', $obj->getFirstName(), PDO::PARAM_STR, 60 );
		$sth->bindParam(':mid_init', $obj->getMidInit(), PDO::PARAM_STR, 1 );
		$sth->bindParam(':last_name', $obj->getLastName(), PDO::PARAM_STR, 60 );
		$sth->bindParam(':addr1', $obj->getAddr1(), PDO::PARAM_STR, 64 );
		$sth->bindParam(':addr2', $obj->getAddr2(), PDO::PARAM_STR, 64 );
		$sth->bindParam(':city', $obj->getCity(), PDO::PARAM_STR, 20 );
		$sth->bindParam(':state', $obj->getState(), PDO::PARAM_INT );
		$sth->bindParam(':zip5', $obj->getZip5(), PDO::PARAM_STR, 5 );
		$sth->bindParam(':zip4', $obj->getZip4(), PDO::PARAM_STR, 4 );
		$sth->bindParam(':email', $obj->getEmail(), PDO::PARAM_STR, 150 );
		$sth->bindParam(':phone', $obj->getPhone(), PDO::PARAM_STR, 32 );
		
		$sth->bindParam(':date_created', $obj->getDateCreated(), PDO::PARAM_STR );

		if ( $type == 'update' )
		{
			$sth->bindParam(':id', $obj->getId(), PDO::PARAM_INT );
		}
		
		$result = $sth->execute();

// print_r( $db->errorInfo() );
// print_r( $sth->errorInfo() );

		if ( $result == 1 )
		{
			if ( $type == 'insert' )
			{
				$obj->setId( $db->lastInsertId() );
			}
			return true;
		}
		return false;
	}
}

