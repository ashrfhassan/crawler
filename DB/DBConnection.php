<?php

require_once __DIR__ . '/../config.php';

class DBConnection
{

    private static $db = null;

    private function __construct()
    {

    }


    /**
     * @brief get MySQL db object
     */
    public static function getDBConnection()
    {

        if (self::$db !== null) {
            return self::$db;
        }

        //creates connection to database
        try {
            mb_internal_encoding("UTF-8");
            mb_regex_encoding("UTF-8");
            date_default_timezone_set("GMT");

            self::$db = new mysqli(MYSQL_MAINDB_SERVER, MYSQL_MAINDB_USER, MYSQL_MAINDB_PASSWORD, MYSQL_MAINDB_NAME);

            self::$db->query("SET CHARACTER SET utf8;");
            self::$db->query("SET time_zone='+00:00';");
        } catch (\Exception $e) {
            die("Cannot open database");
        }
        self::$db->set_charset("utf8");
        return self::$db;
    }

    private function __clone()
    {
        throw new \Exception('you can\'t clone this object');
    }

}
