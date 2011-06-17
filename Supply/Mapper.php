<?php 

require_once dirname(__FILE__) . '/../../config.php';
require_once 'EP/Util/Database.php';

/**
 * 
 * Base mapper class
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Mapper
{
	protected $_usePublicVars = false;
	protected $_dbConn = null;
	protected $_limit = 20;
	protected $_offset = 0;
	
	public function setUsePublicVars( $bool )
	{
		$this->_usePublicVars = $bool;
		return $this;
	}
	
	public function getUsePublicVars()
	{
		return $this->_usePublicVars;
	}
	
	public function setLimit( $limit )
	{
		$this->_limit = $limit;
		return $this;
	}
	
	public function getLimit()
	{
		return $this->_limit;
	}
	
	public function setOffset( $offset )
	{
		$this->_offset = $offset;
		return $this;
	}

	public function getOffset( )
	{
		return $this->_offset;
	}
	
	/**
	 * 
	 * Set the database connection ( preferably PDO )
	 * @param object $conn
	 */
	public function setDatabaseConnection( $conn )
	{
		$this->_dbConn = $conn;
	}
	
	/**
	 * 
	 * Gets a database connection, either a default connection
	 * or one that has been set
	 * @return object db connection
	 */
	public function getDatabaseConnection()
	{
		if ( $this->_dbConn == null )
		{
			$db = EP_Util_Database::pdo_connect();
		}
		else
		{
			$db = $this->_dbConn;
		}
		return $db;
	}
}

