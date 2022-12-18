<?php declare(strict_types = 1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ListRoutesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:list
    {filter? : optional filter for version or action (ex. v1 , GET)}
    {--C|nocolour : turn off coloured output}
    {--L|nolinebreak : turn off line breaks in output}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'outputs a list of a routes and route details';

    /**
     * routes list
     *
     * @var array
     */
    protected $routes = [];

    /**
     * Filter string to apply before displaying a row
     *
     * @var string|null
     */
    protected $filterStr = null;

    /**
     * used when property of route is not set
     *
     * @var string
     */
    protected $NONE = '(NONE)';

    /**
     * Returns route name if route or nothing if it is not set
     *
     * @param string[] $route
     *
     * @return string
     */
    protected function getRouteName(array $route) : string
    {
        return isset($route['action']['as']) ? $route['action']['as'] : $this->NONE;
    }

    /**
     * Returns route action if route or nothing if it is not set
     *
     * @param string[] $route
     *
     * @return string
     */
    protected function getRouteAction(array $route) : string
    {
        if (isset($route['action']['uses'])) {
            $action = explode('\\', $route['action']['uses']);

            return end($action);
        }

        return $this->NONE;
    }

    /**
     * Returns route middleware if route or nothing if it is not set
     *
     * @param string[] $route
     *
     * @return string
     */
    protected function getRouteMiddleware(array $route) : string
    {
        if (isset($route['action']['middleware'])) {
            return join(',', $route['action']['middleware']);
        }

        return '';
    }

    /**
     * generates array of available routes
     *
     * @return void
     */
    protected function generateRoutes() : void
    {
        $routes = app()->router->getRoutes();
        foreach ($routes as $route) {
            array_push($this->routes, [
                'action' => $route['method'],
                'uri' => $route['uri'],
                'name' => $this->getRouteName($route),
                'method' => $this->getRouteAction($route),
                'middleware' => $this->getRouteMiddleware($route), ]);
        }
    }

    /**
     * displays header for
     */
    protected function displayHeader() : void
    {
        $this->line('=========================================');
        $this->line('Action | URI | Name | Method | Middleware');
        $this->line('=========================================');
    }

    /**
     * Counts the number of characters match between the strings, stops upon first incorrect character.
     *
     * @param string $str1
     * @param string $str2
     *
     * @return integer
     */
    private function countPrefixString(string $str1, string $str2) : int
    {
        $shortestStr = $str1;
        $longestStr = $str2;

        // Swap strings if $str1 is the longest
        if (strlen($str1) > strlen($str2)) {
            $shortestStr = $str2;
            $longestStr = $str1;
        }

        // Counts all matching characters, case sensitive, stops on first mismatch
        $count = 0;
        for (; $count < strlen($shortestStr); ++$count) {
            if ($shortestStr[$count] != $longestStr[$count]) {
                break;
            }
        }

        return $count;
    }

    /**
     * outputs list of filtered routes
     *
     * @return void
     */
    protected function displayRoutes() : void
    {
        $paddingSize = 9; // DELETE + 3 spaces
        $lastShownURI = null;

        foreach ($this->routes as $route) {
            $output = '';
            $action = $route['action'];
            $middleware = $route['middleware'];
            $paddedAction = str_pad($action, $paddingSize);

            if ($this->option('nocolour')) {
                $output .= $paddedAction;
                $output .= join(' | ', [$route['uri'], $route['name'], $route['method']]);
                $output .= " | [${middleware}]";
            } else {
                $colours = ['GET' => 'yellow', 'POST' => 'magenta', 'PUT'=>'cyan', 'PATCH'=>'cyan', 'DELETE'=>'red'];

                $output .= "<fg={$colours[$action]}>${paddedAction}</>";
                $output .= join(' <fg=gray>|</> ', [$route['uri'], $route['name'], $route['method']]);
                $output .= " <fg=gray>|</> [${middleware}]";
            }

            // check if the line matches the filter
            if (null == $this->filterStr || false != stripos($output, $this->filterStr)) {
                // Add a separating line if there is a big enough difference between the last displayed line
                if (! $this->option('nolinebreak') && $lastShownURI
                    && $this->countPrefixString($lastShownURI, $route['uri']) < 6) {
                    $this->line(
                        '---------------------------------------------------------------------'
                        . '--------------------------------'
                    );
                }

                $lastShownURI = $route['uri'];
                $this->line($output);
            }
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : void
    {
        $this->generateRoutes();

        if ($this->argument('filter')) {
            $this->filterStr = $this->argument('filter');
        }

        usort($this->routes, function ($a, $b) {return strcmp($a['uri'], $b['uri']); });

        $this->displayHeader();
        $this->displayRoutes();
    }
}
