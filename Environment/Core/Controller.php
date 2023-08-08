<?php

namespace Core;
use Exception;

/**
 * Base Controller
 *
 * All controllers from the App directory will extend this
 * base controller and will inherit all its properties and methods
 *
 * PHP Version 8.2.4
 */
abstract class Controller
{
    /**
     * Parameters from the matched route
     * @var array
     */
    protected array $route_params = [];

    /**
     * Class constructor
     * @param array $route_params - Parameters from the route
     */
    public function __construct(array $route_params)
    {
        $this->route_params = $route_params;
    }

    /**
     * Magic method called when a non-existent or inaccessible method is
     * called on an object of this class. Used to execute before and after
     * filter methods on action methods. Action methods need to be named
     * with an "Action" suffix, e.g. indexAction, showAction, etc.
     *
     * @param string $action
     * @param array $args
     *
     * @throws Exception
     *
     * @return void
     */
    public function __call(string $action, array $args)
    {
        $method = $action . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new Exception('Method ' . $method . ' not found in controller ' . get_class($this));
        }
    }

    /**
     * Before filter - called before an action method
     * @return void
     */
    protected function before(): void {}

    /**
     * After filter - called after an action method
     * @return void
     */
    protected function after(): void {}
}