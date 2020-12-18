<?php namespace FreedomCore\OpenAPI\Builder;

use Illuminate\Support\Arr;
use Illuminate\Routing\Route;
use FreedomCore\OpenAPI\DataObjects\Route as RouteDO;

/**
 * Class SpecificationRoutes
 * @package FreedomCore\OpenAPI\Builder
 */
class SpecificationRoutes {

    /**
     * List of security definitions
     * @var array
     */
    private array $securityDefinitions;

    /**
     * List of routes to be ignored
     * @var array
     */
    private array $ignoredRoutes = [];

    /**
     * List of ignored methods
     * @var array
     */
    private array $ignoredMethods = [];

    /**
     * Routes list
     * @var array|RouteDO[]
     */
    private array $routes = [];

    /**
     * List of paths
     * @var array
     */
    private array $paths = [];

    /**
     * List of tags
     * @var array
     */
    private array $tags = [];

    /**
     * SpecificationRoutes constructor.
     * @param array $securityDefinitions
     */
    public function __construct(array $securityDefinitions = []) {
        $this->securityDefinitions = $securityDefinitions;
        $this
            ->loadListOfIgnoredRoutes()
            ->loadListOfIgnoredMethods()
            ->loadRoutesList()
            ->process();
    }

    /**
     * Get list of paths
     * @return array
     */
    public function paths(): array {
        return $this->paths;
    }

    /**
     * Get list of tags
     * @return array
     */
    public function tags(): array {
        return $this->tags;
    }

    /**
     * Load list of paths to be ignored
     * @return SpecificationRoutes
     */
    private function loadListOfIgnoredRoutes(): SpecificationRoutes {
        $ignoredPackages = config('openapi.ignored.packages');
        foreach ($ignoredPackages as $package => $ignored) {
            if ($ignored) {
                switch ($package) {
                    case 'ignition':
                        $this->ignoredRoutes = array_merge($this->ignoredRoutes, $this->_ignitionRoutes());
                        break;
                    case 'passport':
                        $this->ignoredRoutes = array_merge($this->ignoredRoutes, $this->_passportRoutes());
                        break;
                    case 'sanctum':
                        $this->ignoredRoutes = array_merge($this->ignoredRoutes, $this->_sanctumRoutes());
                        break;
                }
            }
        }
        $this->ignoredRoutes = array_merge($this->ignoredRoutes, config('openapi.ignored.routes'));
        return $this;
    }

    /**
     * Load list of ignored methods
     * @return SpecificationRoutes
     */
    private function loadListOfIgnoredMethods(): SpecificationRoutes {
        $this->ignoredMethods = config('openapi.ignored.methods', []);
        return $this;
    }

    /**
     * Load routes list
     * @return SpecificationRoutes
     */
    private function loadRoutesList(): SpecificationRoutes {
        $rawRoutes = array_filter(app('router')->getRoutes()->getRoutes(), function (Route $route): bool {
            $routeAs = Arr::get($route->getAction(), 'as', null);
            $routeUri = $route->uri();
            return !in_array($routeAs, $this->ignoredRoutes) && !in_array($routeUri, $this->ignoredRoutes);
        });
        $this->routes = array_map(function (Route $route): RouteDO {
            return new RouteDO($route);
        }, array_values($rawRoutes));
        return $this;
    }

    /**
     * Process routes list
     * @return SpecificationRoutes
     */
    private function process(): SpecificationRoutes {
        foreach ($this->routes as $route) {
            $instance = new SpecificationRoute($route, $this->ignoredMethods, $this->securityDefinitions);
            [$routeKey, $routeData] = $instance->toArray();
            if (!empty($routeData)) {
                $this->tags = array_merge($this->tags, $instance->tags());
                Arr::set($this->paths, $routeKey, $routeData);
            }
        }
        return $this;
    }

    /**
     * Get list of Ignition specific routes
     * @return string[]
     */
    private function _ignitionRoutes(): array {
        return [
            'ignition.healthCheck',
            'ignition.executeSolution',
            'ignition.shareReport',
            'ignition.scripts',
            'ignition.styles',
        ];
    }

    /**
     * Get list of Laravel Passport specific routes
     * @return string[]
     */
    private function _passportRoutes(): array {
        return [
            'passport.authorizations.authorize',
            'passport.authorizations.approve',
            'passport.authorizations.deny',
            'passport.token',
            'passport.tokens.index',
            'passport.tokens.destroy',
            'passport.token.refresh',
            'passport.clients.index',
            'passport.clients.store',
            'passport.clients.update',
            'passport.clients.destroy',
            'passport.scopes.index',
            'passport.personal.tokens.index',
            'passport.personal.tokens.store',
            'passport.personal.tokens.destroy',
        ];
    }

    /**
     * Get list of Laravel Sanctum specific routes
     * @return string[]
     */
    private function _sanctumRoutes(): array {
        return [
            'sanctum/csrf-cookie',
        ];
    }

}
