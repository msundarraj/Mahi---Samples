<?php 

class EP_Util_String
{
        
        public static function fixPropertyName($prop)
        {
                $prop = strtolower($prop);
                
                switch ($prop)
                {
                        case 'date_added':
                                $newProp = 'DateAdded';
                                break;
                        case 'date_mod':
                                $newProp = 'DateMod';
                        default:
                        if (strpos($prop, '_') === false)
                        {
                           return ucfirst($prop);
                        }
                        $arr = explode('_', $prop);
                       
                        foreach($arr as $num => $val)
                        {
                            $arr[$num] = ucfirst($val);
                        }
                        $newProp = implode('', $arr);                                   
                                break;
                }
                
        return $newProp;
        }
        

}



