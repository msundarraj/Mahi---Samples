<?php 

/**
 * 
 * PDO database class
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Util_Database
{
      /**
        * Creates a database connection
        * @return object Database connection
        */
	   static function pdo_connect()
	   {
	   		global $dbname, $dbhost, $dbuser, $dbpass;
	   		$db = array(
	   			'host' => $dbhost,
	   			'name' => $dbname,
	   			'user' => $dbuser,
	   			'pass' => $dbpass
	   		);

	           $dbhost = $db['host'];
	           $dbname = $db['name'];
	           $dbuser = $db['user'];
	           $dbpass = $db['pass'];

	           $dsn = 'mysql:dbname=' . $dbname . ';host=' . $dbhost;
	           try
	           {
	               $dbh = new PDO($dsn, $dbuser, $dbpass);
	           }
	           catch (PDOException $e)
	           {
	               echo 'Cannot connect to database!';
	               exit();
	           }

	           return $dbh;
	      // }
	   }
}
