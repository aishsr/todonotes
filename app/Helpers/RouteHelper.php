<?php declare(strict_types = 1);

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class RouteHelper
{
    public $filename;

    public $router;

    public $attributes;

    public $initialRouteCount;

    /**
     * Router constructor.
     *
     * @param \Laravel\Lumen\Application $app
     * @param mixed $router
     * @param mixed $attributes
     */
    public function __construct($router, $attributes, string $filename)
    {
        $this->router = $router;
        $this->attributes = $attributes;
        $this->filename = $filename;
    }

    /**
     * Helper function to create a new route
     *
     * @param object $router With the correct middleware, prefix, etc
     * @param array $route Array with keys: method (STRING), uri (string), action (array|string)
     * @param string $file File name
     */
    public function addRoute(array $route)
    {
        $uri = $route['uri'];

        if (isset($this->attributes)) {
            if (isset($this->attributes['prefix'])) {
                $uri = trim($this->attributes['prefix'], '/') . '/' . trim($uri, '/');
            }
        }

        if (is_callable($route['action'])) {
            $action = '{Function Closure}';
        } else {
            $action = json_encode($route['action']);
        }
        $this->router->addRoute($route['method'], $route['uri'], $route['action']);
    }

    /**
     * Helper function to log at the start of the route file
     *
     * @param object $router With the correct middleware, prefix, etc
     * @param string $file File name
     *
     * @return int
     */
    public function logStartRoutes()
    {
        $this->initialRouteCount = count($this->router->getRoutes());
    }

    /**
     * Helper function to log at the end of the route file
     *
     * @param object $router With the correct middleware, prefix, etc
     * @param int $initialRouteCount Current count before creating new routes
     * @param string $file File name
     */
    public function logEndRoutes()
    {
        $finalRouteCount = count($this->router->getRoutes());
        $routesCreated =  $finalRouteCount - $this->initialRouteCount;
    }
}
