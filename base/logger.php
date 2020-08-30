<?php
//crude and basic logger for debugging while making this assignment

class Logger {
    private static $logFile;
    private static $file;
    private static $basedir = '/var/www/html/bootstrap_proj';


    public static function createNewLog() {
        $date = date($GLOBALS['dateFormat']);
        self::$logFile =  self::$basedir."/logs/".$date.".log";

        self::getDir();

        if (!file_exists(self::$logFile)) {
            fopen(self::$logFile, 'w') or exit(1);
        }

        if (!is_writable(self::$logFile)) {
            return null;
        }
    }

    public static function getDir() {
        if (!file_exists(self::$basedir.'/logs') && !is_dir(self::$basedir.'/logs')) {
            mkdir(self::$basedir.'/logs', 0775, true);
        }
    }
    
    //stub: possible to add more log levels, if needed
    public static function log($message) {
        self::writeIntoLog($message);
    }


    public static function writeIntoLog($message) {
        self::createNewLog();

        if (!is_resource(self::$file)) {
            self::open();
        }

        $time = date($GLOBALS['timeFormat']);

        $timeLog = is_null($time) ? "" : "[".$time."] ";
        $message = is_null($message) ? "" : $message;

        fwrite(self::$file, $timeLog.": [ERROR] - ".$message.PHP_EOL);

        self::close();
    }


    private static function open() {
        $openFile = self::$logFile;
        self::$file = fopen($openFile, 'a') or exit(1);
    }

    public static function close() {
        if (self::$file) {
            fclose(self::$file);
        }
    }
}