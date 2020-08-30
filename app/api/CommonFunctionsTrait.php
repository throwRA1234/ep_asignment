<?php

//some functions might be re-used a lot in the API classes, so such functions will be placed here
Trait CommonFunctionsTrait {
    
    public static function lettersOnly($input) {
        if(preg_match('/^[a-zA-Z]+$/', $input)) {
            return true;
        }
        return false;
    }
    
    public static function isAlphanumeric($input) {
        if(preg_match('/^[a-zA-Z0-9]+$/', $input)) {
            return true;
        }
        return false;
    }
    
    //crude regex, could be improved
    public static function validEmail($input) {
        if(preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i', self::fixEmailToken($input))) {
            return true;
        }
        return false;
    }
    
    public static function fixEmailToken($input) {
        //need a special token, $_POST requests do not handle the . (period) character very well. They become an underscore.
        //this is not a good solution by a long shot of course
        return preg_replace('/££££/', '.', $input);
    }
    
    public static function isUUID($input) {
        return preg_match('/^\{?[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}\}?$/', $input);
    }

}
