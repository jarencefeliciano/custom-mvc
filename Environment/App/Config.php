<?php

namespace App;

/**
 * Database Configuration File
 *
 * - Database Host
 * - Database Username
 * - Database Password
 * - Database Name
 *
 * All variables are declared as constants, meaning they are immutable
 *
 * PHP Version 8.2.4
 */
class Config
{
    /**
     * Database Host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database User
     * @var string
     */
    const DB_USER = 'root';

    /**
     * Database Password
     * @var string
     */
    const DB_PASS = '';

    /**
     * Database Name
     * @var string
     */
    const DB_NAME = 'shareposts';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = false;
}