<?php

namespace App\Controllers\Admin;
use Core\Controller;

/**
 * Users Controller
 *
 * PHP Version 8.2.4
 */
class Users extends Controller
{
    /**
     * Before filter - called before an action method
     * @return void
     */
    protected function before(): void {}

    /**
     * Show the index page
     * @return void
     */
    public function indexAction(): void
    {
        echo 'User admin index';
    }

    /**
     * After filter - called after an action method
     * @return void
     */
    protected function after(): void {}
}