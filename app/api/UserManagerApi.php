<?php

/* 
 * This Api should provide the user creation/deletion/update functionalities the admin might need
 */

Class UserManagerApi extends BaseApi {
    use CommonFunctionsTrait;
    
    public static function createUser($params) {
        
        if(!isset($_SESSION['isAdmin'])) {
            throw new \Exception('Non-Admin can not create users...');
        }
        
        if(!isset($params['fname']) || empty($params['fname'])) {
            return json_encode('Missing first name param...');
        }
        
        if(!isset($params['lname']) || empty($params['lname'])) {
            return json_encode('Missing last name param...');
        }
        
        if(!isset($params['email_address']) || empty($params['email_address'])) {
            return json_encode('Missing email_address param...');
        }
        //not currently used, but adds possibility to designate a supervisor at user creation
        if(!isset($params['supervisor_uname']) || empty($params['supervisor_uname'])) {
            $supervisorId = null;
        } else {
            $supervisorId = self::findSupervisor($params['supervisor_uname']);
        }
        
        if(!isset($params['is_supervisor']) || empty($params['is_supervisor'])) {
            $isSupervisor = 0;
        } else {
            $isSupervisor = ($params['is_supervisor'] == 'Yes') ? '1' : '0'; 
        }
        
        if(!isset($params['is_admin']) || empty($params['is_admin'])) {
            $isAdmin = 0;
        } else {
            $isAdmin = (bool)$params['is_admin'];            
        }
           
        if(CommonFunctionsTrait::lettersOnly($params['fname'])) {
            $fname = $params['fname'];
        } else {
            return json_encode('Invalid input for param first name...');
        }
       
        if(CommonFunctionsTrait::lettersOnly($params['lname'])) {
            $lname = $params['lname'];
        } else {
           return json_encode('Invalid input for param last name...');
        }
       
        if(CommonFunctionsTrait::validEmail($params['email_address'])) {
           $emailAddress = CommonFunctionsTrait::fixEmailToken($params['email_address']);
        } else {
           return json_encode('Invalid input for param email_address...');
        }
        
        if(!isset($params['uname']) || empty($params['uname'])) {
            $uname = lcfirst($fname[0].$lname);
        } else {
            if(CommonFunctionsTrait::isAlphanumeric($params['uname'])) {
                $uname = $params['uname'];
            } else {
                $uname = lcfirst($fname[0].$lname);
            }
        }
        
        if(!isset($params['password']) || empty($params['password'])) {
            $passowrd = ucfirst($fname[0].$lname.'123');
        } else {
            if(CommonFunctionsTrait::isAlphanumeric($params['password'])) {
                $password = $params['password'];
            } else {
                $password = ucfirst($fname[0].$lname.'123');
            }
        }
        
        $userCheck = DB::query("SELECT user_name FROM users WHERE user_name = '".$uname."';");
        
              if(($userCheck->num_rows) > 0) {
                  Logger::log('UserManagerApi: user '.$uname.' already exists...');
                  return json_encode('user '.$uname.' already exists...');
              }
        
        $sparams = array(
            array('type' => 's',
               'value' => $lname),
            array('type' => 's',
               'value' => $fname),
            array('type' => 's',
               'value' => $emailAddress),
            array('type' => 's',
               'value' => $supervisorId),
            array('type' => 'i',
               'value' => $isSupervisor),
            array('type' => 'i',
               'value' => $isAdmin),
            array('type' => 's',
               'value' => $uname),
            array('type' => 's',
               'value' => $password)
        );

        DB::callStoredProcedure('call sp_add_user(?,?,?,?,?,?,?,?)', $sparams);
        
        return json_encode(['status' => 'success', 'message' => 'user '.$uname.' has been created!']);        
    }
    
    public static function updateUser($params) {
        
        if(!isset($_SESSION['isAdmin'])) {
            return json_encode('Non-Admin can not update other users...');
        }
        
        if(!isset($params['uname']) || empty($params['uname'])) {
            return json_encode('Missing username param...');
        }
        
        if(CommonFunctionsTrait::isAlphanumeric($params['uname'])) {
            $uname = $params['uname'];
        } else {
            return json_encode('Invalid uname param...');
        }
        
        $userCheck = DB::query("SELECT user_name FROM users WHERE user_name = '".$uname."';");
        
        if(($userCheck->num_rows) == 0) {
            return json_encode('user '.$uname.' not found...');
        }
        
        $currentParams = [];
        $getCurrentParams = DB::query("SELECT fname, lname, email_address, is_supervisor FROM users WHERE user_name = '".$uname."';");
        while ($row = $getCurrentParams->fetch_assoc()) {
            $currentParams[] = $row;
        }
        
        if (isset($params['lname']) && CommonFunctionsTrait::lettersOnly($params['lname'])) {
            $lname = $params['lname'];
        } else {
            $lname = $currentParams[0]['lname'];
        }
        
        if (isset($params['fname']) && CommonFunctionsTrait::lettersOnly($params['fname'])) {
            $fname = $params['fname'];
        } else {
            $fname = $currentParams[0]['fname'];
        }

        if(isset($params['email_address']) && CommonFunctionsTrait::validEmail($params['email_address'])) {
           $emailAddress = CommonFunctionsTrait::fixEmailToken($params['email_address']);
        } else {
           $emailAddress = $currentParams[0]['email_address'];
        }
        
         if(!isset($params['is_supervisor']) || empty($params['is_supervisor'])) {
            $isSupervisor = $currentParams[0]['is_supervisor'];
         } else {
            $isSupervisor = ($params['is_supervisor'] == 'Yes') ? '1' : '0';  
         }
         
        $sparams = array(
            array('type' => 's',
               'value' => $uname),
            array('type' => 's',
               'value' => $fname),
            array('type' => 's',
               'value' => $lname),
            array('type' => 's',
               'value' => $emailAddress),
            array('type' => 'i',
               'value' => $isSupervisor)
        );

         DB::callStoredProcedure('call sp_update_user_test(?,?,?,?,?)', $sparams);
         
        return json_encode(['status' => 'success', 'message' => 'user '.$uname.' has been updated!']);
        
    }
    
    public static function deleteUser($params) {
        
        if(!isset($_SESSION['isAdmin'])) {
            throw new \Exception('Non-Admin can not delete users...');
        }
        
        if(!isset($params['uname']) || empty($params['uname'])) {
            return json_encode('Missing user name param...');
        }
                          
       if(CommonFunctionsTrait::isAlphanumeric($params['uname'])) {
           $uname = $params['uname'];
       } else {
           return json_encode('Invalid input for param user name...');
       }
               
       $userCheck = DB::query("SELECT user_name FROM users WHERE user_name = '".$uname."';");
        
              if(($userCheck->num_rows) == 0) {
                  Logger::log('UserManagerApi: user '.$uname.' not found...');
                  return json_encode('user '.$uname.' not found...');
              }
              
        $sparams = array(
            array('type' => 's',
               'value' => $uname)
        );

        DB::callStoredProcedure('call sp_delete_user(?)', $sparams);
        
        return json_encode(['status' => 'success', 'message' => 'user '.$uname.' has been deleted!']);
        
    }
    
    //we want to be able to have a list of records to view
    public static function listQuery($params) {
        
        if(!isset($params['from']) || empty($params['from'])) {
            $from = 0;
        } else {
            $from = (int)$params['from'];
        }
        
        if(!isset($params['to']) || empty($params['to'])) {
            $to = $from+10;
        } else {
            $to = (int)$params['to'];
        }
        
        $result = [];
        //Full table scan... 
        $query = DB::query("SELECT fname AS `First Name`, lname AS `Last Name`, user_name AS `Username`, email_address AS `Email`, IF(is_supervisor<>0, 'Yes', 'No') AS `Supervisor?` FROM users LIMIT ".$to." OFFSET ".$from.";");
        
        if ($query->num_rows > 0) {
            while($row = $query->fetch_assoc()) {
            $result[] = $row; 
            }   
        } else {
            return json_encode('No results found...');
        }
        return json_encode($result);
    }
    
    //we want to be able to search for a user
    public static function searchQuery($params) {
        if(!isset($params['uname']) || empty($params['uname'])) {
            return json_encode('Missing user name param...');
        }
        
        if(CommonFunctionsTrait::isAlphanumeric($params['uname'])) {
           $uname = $params['uname'];
        } else {
            return json_encode('Invalid input for param user name...');
        }
               
        $query = DB::query("SELECT fname AS `First Name`, lname AS `Last Name`, user_name AS `Username`, email_address AS `Email`, IF(is_supervisor<>0, 'Yes', 'No') AS `Supervisor?` FROM users WHERE user_name = '".$uname."';");
        
              if(($query->num_rows) == 0) {
                  return json_encode('User '.$uname.' not found...');
              } else {
                  while($row = $query->fetch_assoc()) {
                      $result[] = $row; 
                  }   
              }
        return json_encode($result); 
        
        }
        
        public static function findSupervisor($supervisorUname) {
        if(is_null($supervisorUname)) {
            return null;
        }
        
        if(CommonFunctionsTrait::isAlphanumeric($supervisorUname)) {
           $uname = $supervisorUname;
        } else {
            throw new \Exception('Invalid input for param supervisor user name...');
        }
        
        $result = [];
        
        $query = DB::query("SELECT id FROM users WHERE is_supervisor = 1 AND user_name = '".$uname."' LIMIT 1;");
       
        if(($query->num_rows) == 0) {
            return null;
        } else {
            while ($row = $query->fetch_assoc()) {
                $result[] = $row['id'];
            }
            return $result[0];
        }
            
        }
       
        
    
}

