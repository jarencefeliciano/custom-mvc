<?php

namespace Core;
use App\Config;

use PDO;
use PDOException;

/**
 * Base Model
 * Responsible for handling database query requests
 *
 * PHP Version 8.2.4
 */
abstract class Model
{
    /**
     * Get the PDO Database Connection
     *
     * @return PDO
     */
    protected static function getDB(): PDO
    {
        static $db = null;

        if ($db === null) {
            try {
                $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' .Config::DB_NAME . ';charset=utf8';
                $db = new PDO($dsn, Config::DB_USER, Config::DB_PASS);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        return $db;
    }
}