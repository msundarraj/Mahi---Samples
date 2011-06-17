<?php 

require_once dirname(__FILE__) . '/../../../../config.php';
require_once 'EP/Mapper.php';


/**
 * 
 * Data Mapper for Utility Accounts lists 
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model_Utility_AccountMapper extends EP_Mapper 
{
	
	function fetchAll( $tablePrefix, $inputFields = array(), $fields = array('*'), $orderBy = array('name1') )
	{	
		// set default
		$table = 'duq_data';
		$validTables = array(
			'duq',
			'peco',
			'ppl'
		);
		
		// if the table prefix is set and valid given our schema
		// then we reset the table we're querying
		$tablePrefix = strtolower( $tablePrefix );
		if ( in_array( $tablePrefix, $validTables ))
		{
			$table = $tablePrefix . '_data';
		}
		
		// echo $table . "\n";
		
		$wheres = array();
		
		$name1 = null;
		$name2 = null;
		$addr1 = null;
		$addr2 = null;
		$city = null;
		$state = null;
		$zip5 = null;
		$zip4 = null;
		$account = null;
		
		$validKeys = array(
			'name1',
			'name2',  // name2 isn't even used in database?
			'addr1',
			'addr2',
			'city',
			'state',
			'zip5',
			'zip4',
			'account'
		);
		
		// using some variable interpolation here
		// so $"name1" becomes the variable $name1
		foreach ($inputFields as $k => $v )
		{
			// echo $k . " " . $v . " \n";
			if ( in_array( $k, $validKeys ) )// && !empty( $possibleFields[$k]))
			{
				$$k = trim( $v );
			}
		}

		
		$name2Boolean = '';
		
		// name2 is set and has multiple words
		// like "van horn" we need to add + sign 
		// to each word like "+van +horn" to make good use of fulltext index
		if ( !empty( $name2 ))
		{
			$name2Arr = explode( ' ', $name2 );
			if ( count( $name2Arr ) > 1 )
			{
				$name2 = null;
				for ( $i = 0; $i < count( $name2Arr ); $i++ )
				{
					$name2 .= "+" . $name2Arr[$i] . " "; 
				}
				$name2 = trim( $name2 );
				
				$name2Boolean = ' IN BOOLEAN MODE ';
			}
		}
		

		$name1 = $this->formatParamValue( $name1 );
		$addr1 = $this->formatParamValue( $addr1 );
		$addr2 = $this->formatParamValue( $addr2 );
		$city = $this->formatParamValue( $city );		
		
		$sqlWhereNameCombined = null;
		if ( !empty( $name1) && !empty( $name2 ))
		{
			$sqlWhereNameCombined = "( MATCH(name1) AGAINST( :name1 IN BOOLEAN MODE) AND ";
			$sqlWhereNameCombined .= "MATCH(name1) AGAINST( :name2 $name2Boolean ) ) ";
			
			array_push( $wheres, $sqlWhereNameCombined );
		}
		else 
		{ 
			if ( !empty( $name1 ))
			{
				array_push( $wheres, "MATCH(name1) AGAINST( :name1 IN BOOLEAN MODE) " );
			}
			
			if ( !empty( $name2 ))
			{
				array_push( $wheres, "MATCH(name1) AGAINST( :name2 $name2Boolean ) " );
			} 		
		}
		
		
		if ( !empty( $addr1 ))
		{
			array_push( $wheres, "MATCH(addr1,baddr1) AGAINST( :addr1 IN BOOLEAN MODE) " );
		}	


		if ( !empty( $addr2 ))
		{
			array_push ( $wheres, "MATCH(addr2,baddr2) AGAINST( :addr2 IN BOOLEAN MODE) " );
		}

		if ( !empty( $city ))
		{
			array_push( $wheres, "MATCH(city,bcity) AGAINST( :city IN BOOLEAN MODE) " );
		}

		if ( !empty( $state ))
		{
			array_push( $wheres, "( state = :state OR bstate = :state2 ) ");
		}

		if ( !empty( $zip5 ) && !empty( $zip4 ))
		{
			array_push( $wheres, "(zip = :zip OR bzip = :bzip ) " );
		}
		else
		{
			if ( !empty( $zip5 ))
			{
				array_push( $wheres, "(zip LIKE :zip5 OR bzip LIKE :bzip5 ) " );
			}
		
			if ( !empty( $zip4 ))
			{
				array_push( $wheres, "(zip LIKE :zip4 OR bzip LIKE :bzip4 ) " );
			}
		}
		
		if ( !empty( $account ))
		{
			// array_push( $wheres, "account = :account " );	
			array_push( $wheres, "account LIKE :account " );	
		}
		
		$sql = "SELECT " . implode( ',', $fields );

		$sql .= " FROM $table WHERE ";
		// print_r( $wheres );
		$sqlWhere = implode( ' AND ', $wheres );
		$sql = $sql . $sqlWhere;	
		$sql .= " ORDER BY " . implode( ',', $orderBy );
		$sql .= " LIMIT " . $this->getOffset() . ", " . $this->getLimit() . " ";
		
//echo  "\n" .$sql . "\n";
		$db = EP_Util_Database::pdo_connect();
		$sth = $db->prepare( $sql );
//print_r( $db->errorInfo() );
//print_r( $sth->errorInfo() );
		$sth->setFetchMode( PDO::FETCH_OBJ );
	
		
		// Let's bind our params!
		// We use the lowest common denominator for string lengths
		// when looking at the existing tables
		// so if one table supports 35, but another only supports 30,
		// we'll use 30 for bind statement
		if ( !empty( $name1 ))
		{
			$sth->bindParam( ':name1', $name1, PDO::PARAM_STR, 35 );
			// echo "name1: $name1 \n";
		}
		if ( !empty( $name2 ))
		{
			$sth->bindParam( ':name2', $name2, PDO::PARAM_STR, 35 );
			// echo "name2: $name2 \n";
		}
		if ( !empty( $addr1 ))
		{
			$sth->bindParam( ':addr1', $addr1, PDO::PARAM_STR, 35 );
			// echo "addr1: $addr1 \n";
		}
		if ( !empty( $addr2 ))
		{
			$sth->bindParam( ':addr2', $addr2, PDO::PARAM_STR, 35 );
			// echo "addr2: $addr2 \n";
		}
		if ( !empty( $city ))
		{
			$sth->bindParam( ':city', $city, PDO::PARAM_STR, 30 );
			// echo "city: $city \n";
		}
		if ( !empty( $state ))
		{
			$sth->bindParam( ':state', $state, PDO::PARAM_STR, 2 );
			$sth->bindParam( ':state2', $state, PDO::PARAM_STR, 2 );
			// echo "state: $state \n";
		}	
		
		if ( !empty( $zip5 ) && !empty( $zip4 ))
		{
			$zip = $zip5. $zip4;
			$sth->bindParam( ':zip', $zip, PDO::PARAM_STR, 9 );
			$sth->bindParam( ':bzip', $zip, PDO::PARAM_STR, 9 );
			// echo "zip: $zip \n";
		}
		else 
		{
			if ( !empty( $zip5 ))
			{
				$zip5 = $zip5 . "%";
				$sth->bindParam( ':zip5', $zip5, PDO::PARAM_STR, 9);
				$sth->bindParam( ':bzip5', $zip5, PDO::PARAM_STR, 9);
				// echo "zip5: $zip5 \n";
			}
			
			if ( !empty( $zip4 ))
			{
				$zip4 = "%" . $zip4;
				$sth->bindParam( ':zip4', $zip4, PDO::PARAM_STR, 9);
				$sth->bindParam( ':bzip4', $zip4, PDO::PARAM_STR, 9);
				// echo "zip4: $zip4 \n";
			}
		}		
		
		if ( !empty( $account ))
		{
			$account = "%" . $account . "%";
			$sth->bindParam( ':account', $account, PDO::PARAM_STR );
			// echo "account: $account \n ";
		}
			
		$sth->execute();
		$results = $sth->fetchAll();
		
// print_r( $db->errorInfo() );
// print_r( $sth->errorInfo() );		
// print_r( $results );
		// $sth->closeCursor();
		return $results;
	
	}
	
	public function fetchStreetSuffixes()
	{
		// Note - there is a table called street_suffixes in database
		// however it's kind of dumb to have this data in db since it never changes
		// and there aren't that many entries
		// Ideally, you want this in database but to be cached using memcache
		// or some other similar tool
		$arr = array(
			'boulevard',
			'street',
			'road',
			'avenue',
			'highway',
			'circle',
			'lane',
			'ln',
			'circ',
			'hwy',
			'ave',
			'rd',
			'st',
			'blvd',
			'blvd.',
			'st.',
			'rd.',
			'ave.',
			'hwy.',
			'circ.',
			'ln.'
		);
		
		return $arr;
	}
	
	protected function formatParamValue( $param )
	{
		if ( empty( $param ))
		{
			return $param;
		}
		
		// addr1 is set and has multiple words
		// like "oakwoord cir" we need to add + sign 
		// to each word like "+oakwood +cir" to make good use of fulltext index
		if ( !empty( $param ))
		{
			$paramArr = explode( ' ', $param );
			if ( count( $paramArr ) > 1 )
			{
				$param = null;
				for ( $i = 0; $i < count( $paramArr ); $i++ )
				{
					$param .= "+" . $paramArr[$i] . " "; 
				}
				$param = trim( $param );
			}
			else 
			{
				// we have to set up the full param string as
				// we can only bind one thing in the AGAINST
				// so we're really binding   AGAINST(:param :param* IN BOOLEAN MODE)
				// using ( searchstring) this will match:
				//	(10)  = 10 Oak st
				//  (10)  = 1000 Oak str
				// 	( oak )	= 10 Oak st
				//  ( oak ) = 10 Oakfield st
				$param = $param . " " . $param . "*";
			}
		}
		
		return $param;
	}
}




