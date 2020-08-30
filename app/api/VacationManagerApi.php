<?php

/* 
 * This Api should provide the functionalities needed for the vacation requests
 */

Class VacationManagerApi extends BaseApi {
    use CommonFunctionsTrait;
    
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
        
        $currentUser = $_SESSION['id'];
        
        if(CommonFunctionsTrait::isUUID($_SESSION['id']) == false) {
            return json_encode('User id not found...');
        }
        
        $result = [];
        
        $query = DB::query("SELECT date_start AS `Date From`, date_end AS `Date To`, date_entered AS `Date Submitted`, number_of_days AS `Number of Days`, IFNULL(decision,'Pending') AS `Status` FROM vacations WHERE requested_by_id = '".$currentUser."' LIMIT ".$to." OFFSET ".$from.";");
        
        if ($query->num_rows > 0) {
            while($row = $query->fetch_assoc()) {
            $result[] = $row; 
            }   
        } else {
            return json_encode('No results found...');
        }
        return json_encode($result);
        
    }
    
    public static function searchQuery($params) {
        $currentUser = $_SESSION['id'];
        
        if(CommonFunctionsTrait::isUUID($_SESSION['id']) == false) {
            return json_encode('User id not found...');
        }
        
        $result = [];
        
        $query = DB::query("SELECT date_start AS `Date From`, date_end AS `Date To`, date_entered AS `Date Submitted`, number_of_days AS `Number of Days`, IFNULL(decision,'Pending') AS `Status` FROM vacations WHERE requested_by_id = '".$currentUser."' AND decision IS NULL;");
        
        if ($query->num_rows > 0) {
            while($row = $query->fetch_assoc()) {
            $result[] = $row; 
            }   
        } else {
            return json_encode('No results found...');
        }
        return json_encode($result);
        
    }
    
    public static function createNewRequest($params) {
        if(!isset($params['date_from']) || empty($params['date_from'])) {
            return json_encode('missing date_from param...');
        }
        if(!isset($params['date_to']) || empty($params['date_to'])) {
            return json_encode('missing date_to param...');
        }
        
        if(preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/', $params['date_from'])) {
            $from = $params['date_from'];
        } else {
            return json_encode('invalid date_from param...');
        }
        
        if(preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/', $params['date_to'])) {
            $to = $params['date_to'];
        } else {
            return json_encode('invalid date_to param...');
        }
        
        $dateDiff = date_diff(date_create($from), date_create($to))->format("%R%a");

        if(preg_match('/^\-/', $dateDiff)) {
            return json_encode('Negative days are not allowed...');
        }
        
        $description = preg_replace('/[^\w\s&,.!?]/', '', $params['description']);
        
        $currentUser = $_SESSION['id'];
        $currentUserUname = $_SESSION['username'];
        
        if(CommonFunctionsTrait::isUUID($currentUser) == false) {
            return json_encode('User id not found...');
        }
        
        //this is an awful solution, in reality, better to use ramsey/uuid via composer
        $uuid = file_get_contents('/proc/sys/kernel/random/uuid');
        
        $sparams = array(
            array('type' => 's',
               'value' => $uuid),
            array('type' => 's',
               'value' => $currentUser),
            array('type' => 's',
               'value' => $description),
            array('type' => 's',
               'value' => $from),
            array('type' => 's',
               'value' => $to)
        );

        DB::callStoredProcedure('call sp_create_vacation_request(?,?,?,?,?)', $sparams);
        
        $text = <<<EOF
Dear supervisor, employee {$currentUserUname} requested for some time off, starting on<br />
{$from} and ending on {$to}, stating the reason:<br />
{$description}<br />
Click on one of the below links to approve or reject the application:<br />
<a href="http://localhost/bootstrap_proj/api/client/makeDecision?controller=VacationManager&uuid={$uuid}&decision=Approved">Approve</a> - <a href="http://localhost/bootstrap_proj/api/client/makeDecision?controller=VacationManager&uuid={$uuid}&decision=Rejected">Reject</a>
EOF;
        //for now, emails are hardcoded to go to admin
        sendMail($GLOBALS['adminEmail'], '[INFO]', $text, true);
        
        return json_encode(['status' => 'success', 'message' => 'Your request has been submitted...']);
        
    }
    
    public static function makeDecision($params) {
        if(!isset($params['uuid']) || empty($params['uuid'])) {
            return json_encode('missing uuid param...');
        }
        
        if(!isset($params['decision']) || empty($params['decision'])) {
            return json_encode('missing decision param...');
        }
        
        if(CommonFunctionsTrait::isUUID($params['uuid']) == false) {
            return json_encode('invalid uuid param...');
        } else {
            $uuid = $params['uuid'];
        }
        
        if(CommonFunctionsTrait::isAlphanumeric($params['decision']) == false) {
            return json_encode('invalid decision param...');
        } else {
            $decision = $params['decision'];
        }
        
        $query = DB::query("SELECT v.requested_by_id AS `req`, v.date_entered AS `de`, u.email_address AS `ea` FROM vacations v JOIN users u ON v.requested_by_id = u.id WHERE v.id = '".$uuid."';");
        
        $dateEntered = [];
        $emailAddress = [];
        if(($query->num_rows) == 0) {
            return json_encode('vacation request not found...');
        } else {
            while ($row = $query->fetch_assoc()) {
                $dateEntered[] = $row['de'];
                $emailAddress[] = $row['ea'];
            }
        }
        
        $sparams = array(
            array('type' => 's',
               'value' => $uuid),
            array('type' => 's',
               'value' => $decision)
        );

        DB::callStoredProcedure('call sp_update_vacation_request(?,?)', $sparams);
        
        $text = 'Dear employee, your supervisor has '.$decision.' your application submitted on '.$dateEntered[0];
        
        sendMail($emailAddress[0], '[INFO]', $text, false);
        
    }
    
}

