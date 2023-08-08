<?php

namespace Core;
use Exception;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * Base View
 * Handles templates
 *
 * PHP Version 8.2.4
 */
class View
{
    /**
     * Render a view file
     *
     * @param string $view - The view file
     * @param array $args
     *
     * @throws Exception
     *
     * @return void
     */
    public static function render(string $view, array $args = []): void
    {
        # Convert associative of array into an individual variables
        extract($args, EXTR_SKIP);

        $file = '../Environment/App/Views/' . $view;

        if (is_readable($file)) {
            require_once $file;
        } else {
            throw new Exception($file . ' not found!');
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template - The template file
     * @param array $args - Associative array of data to display in the view (optional)
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @return void
     */
    public static function renderTemplate(string $template, array $args = []): void
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new FilesystemLoader('../Environment/App/Views');
            $twig = new Environment($loader);
        }

        echo $twig->render($template, $args);
    }
}