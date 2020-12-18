<?php namespace FreedomCore\OpenAPI;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use FreedomCore\OpenAPI\Builder\SpecificationBase;
use FreedomCore\OpenAPI\Builder\SpecificationRoutes;
use FreedomCore\OpenAPI\Builder\SpecificationServers;
use FreedomCore\OpenAPI\Builder\SpecificationComponents;
use FreedomCore\OpenAPI\Exceptions\InvalidAuthenticationFlow;
use FreedomCore\OpenAPI\Exceptions\InvalidDefinitionException;

/**
 * Class OpenAPI
 * @package FreedomCore\OpenAPI
 */
class OpenAPI {

    /**
     * Specification Base instance
     * @var SpecificationBase
     */
    protected SpecificationBase $base;

    /**
     * Specification Servers instance
     * @var SpecificationServers
     */
    protected SpecificationServers $servers;

    /**
     * Specification Routes instance
     * @var SpecificationRoutes
     */
    protected SpecificationRoutes $routes;

    /**
     * Specification Components instance
     * @var SpecificationComponents
     */
    protected SpecificationComponents $components;

    /**
     * OpenAPI constructor.
     * @throws InvalidAuthenticationFlow|InvalidDefinitionException
     */
    public function __construct() {
        $this->base = new SpecificationBase;
        $this->servers = new SpecificationServers;
        $securityDefinitions = $this->generateSecurityDefinitions();
        $this->routes = new SpecificationRoutes($securityDefinitions);
        $this->components = new SpecificationComponents($securityDefinitions);
    }

    public function documentation(): array {
        return array_merge($this->base->toArray(), [
            'tags'          =>  array_values($this->routes->tags()),
            'servers'       =>  $this->servers->servers(),
            'paths'         =>  $this->routes->paths(),
            'components'    =>  $this->components->toArray()
        ]);
    }

    /**
     * Generate security definitions
     * @return array
     * @throws InvalidAuthenticationFlow|InvalidDefinitionException
     */
    private function generateSecurityDefinitions(): array {
        $authenticationFlows = config('openapi.authentication_flow');
        $definitions = [];

        foreach ($authenticationFlows as $definition => $flow) {
            if ($definition === 'OAuth2' && !$this->isPassportInstalled()) {
                continue;
            }
            $this->validateAuthenticationFlow($definition, $flow);
            $definitions[$definition] = $this->createSecurityDefinition($definition, $flow);
        }
        return $definitions;
    }

    /**
     * Validate authentication flow
     * @param string $definition
     * @param string $flow
     * @throws InvalidAuthenticationFlow|InvalidDefinitionException
     */
    private function validateAuthenticationFlow(string $definition, string $flow): void {
        $definitions = [
            'OAuth2'            =>  ['password', 'application', 'implicit', 'authorizationCode'],
            'bearerAuth'        =>  ['http']
        ];

        if (!Arr::has($definitions, $definition)) {
            throw new InvalidDefinitionException('Invalid Definition, please select from the following: ' . implode(', ', array_keys($definitions)));
        }

        $allowed = $definitions[$definition];
        if (!in_array($flow, $allowed)) {
            throw new InvalidAuthenticationFlow('Invalid Authentication Flow, please select one from the following: ' . implode(', ', $allowed));
        }
    }

    /**
     * Create security definition
     * @param string $definition
     * @param string $flow
     * @return array|string[]
     */
    private function createSecurityDefinition(string $definition, string $flow): array {
        switch ($definition) {
            case 'OAuth2':
                $definitionBody = [
                    'type'      =>  'oauth2',
                    'flows'      =>  [
                        $flow => []
                    ],
                ];
                $flowKey = 'flows.' . $flow . '.';
                if (in_array($flow, ['implicit', 'authorizationCode'])) {
                    Arr::set($definitionBody, $flowKey . 'authorizationUrl', $this->getEndpoint('/oauth/authorize'));
                }

                if (in_array($flow, ['password', 'application', 'authorizationCode'])) {
                    Arr::set($definitionBody, $flowKey . 'tokenUrl', $this->getEndpoint('/oauth/token'));
                }
                Arr::set($definitionBody, $flowKey . 'scopes', $this->generateOAuthScopes());
                return $definitionBody;
            case 'bearerAuth':
                return [
                    'type'          =>  $flow,
                    'scheme'        =>  'bearer',
                    'bearerFormat'  =>  'JWT'
                ];
        }
        return [];
    }

    /**
     * Get endpoint
     * @param string $path
     * @return string
     */
    private function getEndpoint(string $path): string {
        $host = config('openapi.api.host');
        if (!Str::startsWith($host,'http://') || !Str::startsWith($host,'https://')) {
            $schema = openapi_is_connection_secure() ? 'https://' : 'http://';
            $host = $schema . $host;
        }
        return rtrim($host, '/') . $path;
    }

    /**
     * Generate OAuth scopes
     * @return array
     */
    private function generateOAuthScopes(): array {
        if (!$this->isPassportInstalled()) {
            return [];
        }

        $scopes = \Laravel\Passport\Passport::scopes()->toArray();
        return array_combine(array_column($scopes, 'id'), array_column($scopes, 'description'));
    }

    /**
     * Check if Laravel Passport installed
     * @return bool
     */
    private function isPassportInstalled(): bool {
        return class_exists('\Laravel\Passport\Passport');
    }

}
