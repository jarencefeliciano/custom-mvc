<?php

namespace Core;
use Exception;

/**
 * Router
 * A class that handles URL requests
 * Format: controller/action/id
 *
 * PHP Version 8.2.4
 */
class Router
{
    /**
     * Associative array of routes (the routing table)
     * @var array
     */
    private array $routes = [];

    /**
     * Parameters from the matched route
     * @var array
     */
    private array $params = [];

    /**
     * Add a route to the routing table
     *
     * @param string $route - The route URL
     * @param array $params - Parameters (controller, action, etc.)
     *
     * @return void
     */
    public function add(string $route, array $params = []): void
    {
        # Convert the route to a regular expression

        # Escaping the / and replace it with \/
        $route = preg_replace('/\//', '\\/', $route);

        # Convert variables e.g. {controller}
        $route = preg_replace('/{([a-z]+)}/', '(?P<\1>[a-z-]+)', $route);

        # Convert variables with custom regular expressions e.g. {id:\d+}
        $route = preg_replace('/{([a-z]+):([^}]+)}/', '(?P<\1>\2)', $route);

        # Add start and end delimiters, (i) case-insensitive flag and (u) utf8 modifier to allow non-English characters
        $route = '/^' . $route . '$/iu';

        $this->routes[$route] = $params;
    }

    /**
     * Get all the routes from the routing table
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Match the URL route to the routes in the routing table,
     * setting the $params property if a route is found and then
     * returns true, if there's no match returns false
     *
     * @param $url - The route URL
     *
     * @return bool
     */
    public function match($url): bool
    {
        $url = urldecode($url); # slug:\pL

        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Get the currently matched parameters
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Dispatch the route, creating the controller object and
     * running the action method
     *
     * @param string $url - The route URL
     *
     * @throws Exception
     *
     * @return void
     */
    public function dispatch(string $url): void
    {
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller;

            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                if (preg_match('/action$/i', $action) == 0) {
                    $controller_object->$action();
                } else {
                    throw new Exception('Method ' . $action . ' (in controller ' . $controller . ') not found');
                }
            } else {
                throw new Exception('Controller class ' . $controller . ' not found!');
            }
        } else {
           throw new Exception('No route found!', 404);
        }
    }

    /**
     * Convert the string with hyphens to StudlyCaps
     * E.g. post-authors => PostAuthors
     *
     * @param string $string - The string to convert
     *
     * @return string
     */
    private function convertToStudlyCaps(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Convert the string with hyphens to camelCase
     * E.g. add-new => addNew
     *
     * @param string $string - The string to convert
     *
     * @return string
     */
    private function convertToCamelCase(string $string): string
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Remove the query string variables from the URL (if any).
     * As the full query string is used for the route, any variables
     * at the end will need to be removed before the route is matched
     * to the routing table.
     *
     * For example:
     * URL                              $_SERVER['QUERY_STRING']                Route
     * ------------------------------------------------------------------------------
     * localhost                        ''                                      ''
     * localhost/?                      ''                                      ''
     * localhost/?page=1                page=1                                  ''
     * localhost/posts?page=1           posts&page=1                            posts
     * localhost/posts/index            posts/index                             posts/index
     * localhost/posts/index?page=1     posts/index&page=1                      posts/index
     *
     * A URL of the format localhost/?page (one variable name, no value)
     * won't work, however. (NB. The .htaccess file converts the first ? to a & when
     * it's passed through the $_SERVER variable).
     *
     * @param string $url - The full URL
     *
     * @return string
     */
    private function removeQueryStringVariables(string $url): string
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);

            $url = (!str_contains($parts[0], '=')) ? $parts[0] : '';
        }
        return $url;
    }

    /**
     * Get the namespace for the controller class. The namespace defined
     * in the route parameter is added if present.
     *
     * @return string - The request URL
     */
    private function getNamespace(): string
    {
        $namespace = 'App\Controllers\\';

        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }

        return $namespace;
    }
}