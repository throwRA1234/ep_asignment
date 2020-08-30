<?php
//misc functions that should not be accessible from API
function sendMail($to, $subject, $message, $isHTML = false) {
    
    if($isHTML == false) {
    $headers = 'From: '.$GLOBALS['adminEmail']. "\r\n" .
               'Reply-To: '.$GLOBALS['adminEmail']. "\r\n" .
               'X-Mailer: PHP/' . phpversion();
    }
    else {
    $headers = 'From: '.$GLOBALS['adminEmail']. "\r\n" .
               'Reply-To: '.$GLOBALS['adminEmail']. "\r\n" .
               'X-Mailer: PHP/' . phpversion(). "\r\n" .        
               'MIME-Version: 1.0'. "\r\n" .
               'Content-type: text/html; charset=iso-8859-1';
    }

    mail($to, $subject, $message, $headers);
        
}
?>