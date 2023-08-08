<?php

namespace App\Models;
use Core\Model;

use PDO;
use PDOException;

/**
 * Home Model
 *
 * PHP Version 8.2.4
 */
class User extends Model
{
    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll(): array
    {
        $result = [];

        try {
            $db = static::getDB();
            $stmt = $db->query('SELECT name, email FROM users ORDER BY created_at');
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $result;
    }
}