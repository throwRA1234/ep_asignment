<?php

class UserApi {
    use CommonFunctionsTrait;
    
    public static function login($params) {
        if(!isset($params['username']) || empty($params['username'])) {
            throw new \Exception('Missing username param...');
        }
        
        if(!isset($params['password']) || empty($params['password'])) {
            throw new \Exception('Missing password param...');
        }
        
        if(CommonFunctionsTrait::isAlphanumeric($params['username'])) {
            $uname = $params['username'];
        } else {
            throw new \Exception('Invalid username param...');
        }
        
        $userCheck = DB::query("SELECT id FROM users WHERE user_name = '".$uname."';");
        
        $result = [];
        if(($userCheck->num_rows) == 0) {
            throw new \Exception('user '.$uname.' not found...');
        } else if(($userCheck->num_rows) > 1) {
            throw new \Exception('More than one user with '.$uname.'...?!');
        } else {
            while ($row = $userCheck->fetch_assoc()) {
                $result[] = $row['id'];
            }
        }

        if(!CommonFunctionsTrait::isUUID($result[0])) {
            throw new Exception('UserID should be a UUID...');
        }

        //here it is best to use a parametrized query
        $connection = DB::getConnection();
        $stmt = $connection->prepare("SELECT password FROM user_credentials WHERE user_id=?"); 
        $stmt->bind_param("s", $result[0]);
        $stmt->execute();
        $stmtResult = $stmt->get_result(); 
        $pass = [];
        while ($row = $stmtResult->fetch_assoc()) {
            $pass[] = $row['password'];
        }

        if($pass[0] === $params['password']) {
            $_SESSION['login'] = true;
            $query = DB::query("SELECT id, IF(is_admin<>0, 'Yes', 'No') AS `isAdmin`, IF(is_supervisor<>0, 'Yes', 'No') AS `isSupervisor`, user_name FROM users WHERE user_name = '".$uname."';");
            $isAdmin = []; 
            $isSupervisor = [];
            $username = [];
            $id = [];
            while ($row = $query->fetch_assoc()) {
                $isAdmin[] = $row['isAdmin'];
                $isSupervisor[] = $row['isSupervisor'];
                $username[] = $row['user_name'];
                $id[] = $row['id'];
            }
            if($isAdmin[0] === 'Yes') {
                $_SESSION['isAdmin'] = true;
            }
            if($isSupervisor[0] === 'Yes') {
                $_SESSION['isSupervisor'] = true;
            }
            $_SESSION['username'] = $username[0];
            $_SESSION['id'] = $id[0];
            Logger::log(print_r($_SESSION, true));
            return json_encode('Login Accepted...');
            //then we can consider it a successful login
        } else {
            return json_encode('Wrong password...');
        }
    }
    
    public static function logout() {
        $_SESSION = [];
        session_destroy();
        return json_encode([]);
    }
    
}

