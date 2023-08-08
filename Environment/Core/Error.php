<?php

namespace Core;
use App\Config;
use ErrorException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Error Handling
 * A class that handles error and exception handlers
 *
 * PHP Version 8.2.4
 */
class Error
{
    /**
     * Error handler
     * Convert all errors to Exceptions by throwing an ErrorException
     *
     * @param $level
     * @param $message
     * @param $file
     * @param $line
     *
     * @throws ErrorException
     *
     * @return void
     */
    public static function errorHandler($level, $message, $file, $line): void
    {
        if (error_reporting() !== 0) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Exception handler.
     *
     * @param $exception
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @return void
     */
    public static function exceptionHandler($exception): void
    {
        # Code is 404 (not found) or 500 (general error)
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        http_response_code($code);

        if (Config::SHOW_ERRORS) {
            echo '<h1>Fatal error:</h1>';
            echo '<p>Uncaught exception: "' . get_class($exception) . '"</p>';
            echo '<p>Message: "' . $exception->getMessage() . '"</p>';
            echo '<p>Stack trace: <pre>"' . $exception->getTraceAsString() . '"</pre></p>';
            echo '<p>Thrown in "' . $exception->getFile() . '" on line "' . $exception->getLine() . '"</p>';
        } else {
            $log = dirname(__DIR__) . '/logs/' . date('Y-m-d' . '.txt');
            ini_set('error_log', $log);

            $message = 'Uncaught exception: "' . get_class($exception) . '" ';
            $message .= 'with message "' . $exception->getMessage() . '"';
            $message .= '\nStack trace: "' . $exception->getTraceAsString();
            $message .= '\nThrown in "' . $exception->getFile() . '" on line ' . $exception->getLine();

            error_log($message);
            View::renderTemplate($code . '.html');
        }
    }
}