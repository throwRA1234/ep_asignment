<?php

//force some consistency in the Api Classes
abstract class BaseApi {
    
    abstract public static function listQuery($params);
    
    abstract public static function searchQuery($params);
}

