<?php 

require_once dirname(__FILE__) . '/../../config.php';
require_once 'EP/Util/String.php';

/**
 * 
 * Base model class
 * @author Rich Zygler rzygler@gmail.com
 *
 */
class EP_Model
{
	/**
     * 
     * @var array 
     */
    private $_messages = array();
	
   	public function __construct( $options = null )
	{ 
        if (is_array($options)) 
        {
            $this->setOptions($options);
        }

        $this->init();
    }
    
    private function init()
    {
    	
    }
    
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) 
        {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method )) 
            {
                $this->$method($value);
            }
            else 
            {
                $method = 'set' .  EP_Util_String::fixPropertyName($key);
                if (method_exists($this, $method ))  
                {
                    $this->$method($value);
                }               
            }
        }
        return $this;
    }
    
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if ( !method_exists($this, $method))
        {
            $method = 'set' . EP_Util_String::fixPropertyName($name);
            if ( !method_exists($this, $method))  
            {
                throw new Exception('InvalidProperty ' . $name);
            }
        }
        return $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if ( !method_exists($this, $method)) 
        {
            $method = 'get' . EP_Util_String::fixPropertyName($name);
 
            if ( !method_exists($this, $method)) 
            {
                throw new Exception('InvalidProperty ' . $name);
            }
        }
        return $this->$method();
    }
    
    /**
     * Format the error messages type string to the internal key used in the array
     * @param string $status The error message type (ERROR, SUCCESS or others)
     * @return string
     */
    protected function _formatMessageStatus($status)
    {
        switch(strtoupper(trim($status))){
                CASE 'ERROR':
                CASE 'ERRORS':
                        return 'errors';
                        break;
                CASE 'SUCCESS':
                        return 'successes';
                        break;
                DEFAULT:
                        return 'warnings';      
        }       
    }
    
    /**
     * This function will take a message type and an error message and store it in the internal array
     * @param string $status  ERROR | SUCCESS
     * @param string $msg
     * @return Application_Model
     */
    public function setMessage($status, $msg)
    {
        $status = strtoupper($status);
        
        $statusType = $this->_formatMessageStatus($status);
        if(is_array($msg))
        {
                foreach($msg as $item)
                {
                        $this->_messages[$statusType][] = $item;        
                }       
        }
        else
        {
                $this->_messages[$statusType][] = $msg;
        }
        
        return $this;
    }

    /**
     * Returns a boolean to indicate if there are messages set
     * @param string $status Filter messages to a specific type 
     * @return bool
     */    
    public function hasMessages($status = '')
    {
        if ($status === '')
        {
                if (sizeof($this->_messages) > 0)
                {
                        return true;
                }
                return false;
        } else {
                $statusType = $this->_formatMessageStatus($status);
                if(array_key_exists($statusType, $this->_messages) && sizeof($this->_messages[$statusType]) > 0)
                {
                        return true;
                }
                return false;
        }
    }
    
    /**
     * Returns an array of messages if any. Note that if you don't specify
     * the type of messages with the $status parameter, you will get an array
     * of arrays.
     * @param string $status Filter messages to a specific type
     * @return array 
     */
    public function getMessages($status = '')
    {
        if ($status === '')
        {
                return $this->_messages;
        } else {
                $statusType = $this->_formatMessageStatus($status);
                if (!$this->hasMessages($status))
                {
                        return array();
                } else {
                        return $this->_messages[$statusType];
                }
        }
    }
    
    /**
     * 
     * Returns the list of properties for the given class
     * @param string $class Class name
     */
    public static function getAllClassProperties( $class = null )
    {
    	$props = array();
    	
    	if ( !class_exists( $class ))
    	{
    		return $props; 
    	}
    	
   		$obj = new $class();

		$reflect = new ReflectionClass( $obj );
		$tmpProps   = $reflect->getProperties( ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED );

		foreach ($tmpProps as $prop) 
		{
    		array_push( $props, $prop->getName() );
		}
		
		unset( $obj );
		
		return $props;
    }
	
}

