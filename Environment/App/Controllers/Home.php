<?php

namespace App\Controllers;
use App\Models\User;
use Core\View;
use Core\Controller;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Home Controller
 *
 * PHP Version 8.2.4
 */
class Home extends Controller
{
    /**
     * Before action - called before an action method
     * @return void
     */
     protected function before(): void
     {
         echo "(before)---";
     }

    /**
     * After action - called after an action method
     * @return void
     */
     protected function after(): void
     {
         echo "---(after)";
     }

    /**
     * Show index page
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @return void
     */
    public function indexAction(): void
    {
        $data = User::getAll();

        View::renderTemplate('Home/index.html', ['users' => $data]);
    }

    /**
     * Show create user page
     * @return void
     */
    public function createUserAction(): void
    {
        echo '<p>
            Query String Parameters: 
            <pre>
                ' .htmlspecialchars(print_r($_GET, true)). '
            </pre>
        </p>';
    }

    /**
     * Show update user page
     * @return void
     */
    public function updateUserAction(): void
    {
        echo '<p>
            Parameters: 
            <pre>
                ' .htmlspecialchars(print_r($this->route_params, true)). '
            </pre>
        </p>';
    }
}