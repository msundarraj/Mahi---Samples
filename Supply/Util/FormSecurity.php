<?php
   if (!session_id())
   {
       session_start();
   }

   class EP_Util_FormSecurity
   {
       static function getTokenTag($prefix="")
       {
           if (!(isset($_SESSION['formSecurityTokens'])))
           {
               $_SESSION['formSecurityTokens'] = array();
           }
           $token = uniqid($prefix,true);
           $_SESSION['formSecurityTokens'][] = $token;
           return $token;
       }

       static function validateToken($token)
       {// function validateToken
           if(!(isset($_SESSION['formSecurityTokens'])))
           {
               return false;
           }
           for ($x = 0 ; $x < count($_SESSION['formSecurityTokens']) ; $x++)
           {
               if (strcmp($token,$_SESSION['formSecurityTokens'][$x]) == 0)
               {
                   unset($_SESSION['formSecurityTokens'][$x]);
                   $_SESSION['formSecurityTokens'] = array_values($_SESSION['formSecurityTokens']);
                   return true;
               }
           }
           return false;
       }// end function validateToken
   }
