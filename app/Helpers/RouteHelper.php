<?php declare(strict_types = 1);

namespace App\Helpers;

class RouteHelper
{
    public $filename;

    public $router;

    public $attributes;

    public $initialRouteCount;

    /**
     * Router constructor.
     *
     * @param \Laravel\Lumen\Application $app Main Laravel Application
     * @param mixed $router
     * @param mixed $attributes Route attributes such as namespace, prefix, middleware, ...
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
     * @param object $router With the correct middleware, prefix, etc.
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
        $this->router->addRoute($route['method'], $route['uri'], $route['action']);
    }
}
