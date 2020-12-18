<?php namespace FreedomCore\OpenAPI\Builder;

use Exception;
use ReflectionMethod;
use ReflectionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use FreedomCore\OpenAPI\DataObjects\Route;
use Illuminate\Foundation\Http\FormRequest;
use phpDocumentor\Reflection\DocBlockFactory;
use FreedomCore\OpenAPI\DataObjects\Middleware;
use FreedomCore\OpenAPI\Generators\BodyParameters;
use FreedomCore\OpenAPI\Generators\PathParameters;
use FreedomCore\OpenAPI\Generators\QueryParameters;
use FreedomCore\OpenAPI\Attributes\Request\Request;
use FreedomCore\OpenAPI\Attributes\Response\Response;

/**
 * Class SpecificationRoute
 * @package FreedomCore\OpenAPI\Builder
 */
class SpecificationRoute {

    /**
     * List of security definitions
     * @var array
     */
    private array $securityDefinitions;

    /**
     * Parser instance
     * @var DocBlockFactory
     */
    private DocBlockFactory $parser;

    /**
     * Route Data Object instance
     * @var Route
     */
    private Route $route;

    /**
     * List of ignored methods
     * @var array
     */
    private array $ignoredMethods = [];

    /**
     * Route uri
     * @var string
     */
    private string $uri;

    /**
     * Route data
     * @var array
     */
    private array $data = [];

    /**
     * Route class tags
     * @var array
     */
    private array $tags = [];

    /**
     * SpecificationRoute constructor.
     * @param Route $route
     * @param array $ignoredMethods
     * @param array $securityDefinitions
     */
    public function __construct(Route $route, array $ignoredMethods = [], array $securityDefinitions = []) {
        $this->parser = DocBlockFactory::createInstance();
        $this->route = $route;
        $this->ignoredMethods = $ignoredMethods;
        $this->securityDefinitions = $securityDefinitions;
        $this->uri = $this->route->uri();
        $this->process();
    }

    /**
     * Get route information
     * @return array
     */
    public function toArray(): array {
        return [$this->uri, $this->data];
    }

    /**
     * Get list of route tags
     * @return array
     */
    public function tags(): array {
        return $this->tags;
    }

    /**
     * Process route information
     * @return void
     */
    private function process(): void {
        foreach ($this->route->methods() as $method) {
            if (!in_array($method, $this->ignoredMethods)) {
                $methodInstance = $this->getMethodInstance();
                if ($methodInstance) {
                    $this->data[$method] = $this->processMethod($method, $methodInstance);
                }
            }
        }
    }

    /**
     * Get method instance
     * @return ReflectionMethod|null
     */
    private function getMethodInstance(): ?ReflectionMethod {
        [$class, $method] = Str::parseCallback($this->route->action());
        if ($class && $method) {
            try {
                return new ReflectionMethod($class, $method);
            } catch (ReflectionException) {}
        }
        return null;
    }

    /**
     * Process method
     * @param string $method
     * @param ReflectionMethod $instance
     * @return array
     */
    private function processMethod(string $method, ReflectionMethod $instance): array {
        $rawDocumentationBlock = $instance->getDocComment() ?: '';
        $documentation = $this->parseMethodParameters(
            $this->parseMethodDocumentation(
                $instance,
                $rawDocumentationBlock
            ),
            $method,
            $instance
        );
        $this->parseMethodTags($documentation, $instance);
        $this->addActionScopes($documentation);
        return $documentation;
    }

    /**
     * Parse method documentation
     * @param ReflectionMethod $instance
     * @param string $rawDocumentationBlock
     * @return array
     */
    private function parseMethodDocumentation(ReflectionMethod $instance, string $rawDocumentationBlock): array {
        $documentation = [
            'summary'       =>  '',
            'description'   =>  '',
            'deprecated'    =>  false,
            'responses'     =>  []
        ];

        try {
            $parsedDocBlock = $this->parser->create($rawDocumentationBlock);
            Arr::set($documentation, 'summary', $parsedDocBlock->getSummary());
            Arr::set($documentation, 'description', (string) $parsedDocBlock->getDescription());
            Arr::set($documentation, 'deprecated', $parsedDocBlock->hasTag('deprecated'));

            foreach ($instance->getAttributes() as $attribute) {
                $attributeInstance = $attribute->newInstance();
                if ($attributeInstance instanceof Request) {
                    Arr::set($documentation, 'description', $attributeInstance->description);
                    Arr::set($documentation, 'deprecated', $attributeInstance->deprecated);
                }
                if ($attributeInstance instanceof Response) {
                    Arr::set($documentation, 'responses.' . $attributeInstance->code, [
                        'description'       =>  $attributeInstance->description
                    ]);
                }
            }
        } catch (Exception) {}

        if (count(Arr::get($documentation, 'responses')) === 0) {
            Arr::set($documentation, 'responses', [
                200                 =>  [
                    'description'   =>  'Ok'
                ]
            ]);
        }

        return $documentation;
    }

    /**
     * Parse method parameters
     * @param array $documentation
     * @param string $method
     * @param ReflectionMethod $instance
     * @return array
     */
    private function parseMethodParameters(array $documentation, string $method, ReflectionMethod $instance): array {
        $rules = $this->retrieveFormRules($instance);
        $pathParameters = (new PathParameters($this->route->originalUri(), $instance))->parameters();
        $queryParameters = (new QueryParameters($this->route->originalUri(), $rules, $instance))->parameters();
        $bodyParameters = (new BodyParameters($this->route->originalUri(), $rules, $instance))->parameters();

        if (!empty($bodyParameters) && in_array($method, ['post', 'put', 'patch'])) {
            Arr::set($documentation, 'requestBody', $bodyParameters);
        } else {
            $parameters = array_merge($pathParameters, $queryParameters);
            if (!empty($parameters)) {
                Arr::set($documentation, 'parameters', array_merge($pathParameters, $queryParameters));
            }
        }

        return $documentation;
    }

    /**
     * Parse method tags
     * @param array $documentation
     * @param ReflectionMethod $instance
     */
    private function parseMethodTags(array & $documentation, ReflectionMethod $instance): void {
        $classAttributes = $instance->getDeclaringClass()->getAttributes();
        foreach ($classAttributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            $tempData = [
                'name'          =>  $attributeInstance->name,
                'description'   =>  $attributeInstance->description,
            ];

            if (!empty($attributeInstance->externalDocs)) {
                $tempData['externalDocs'] = $attributeInstance->externalDocs;
            }

            $this->tags[$attributeInstance->name] = $tempData;
            $documentation['tags'][] = $attributeInstance->name;
        }
    }

    /**
     * Add action scopes
     * @param array $documentation
     */
    private function addActionScopes(array & $documentation): void {
        foreach ($this->route->middleware() as $middleware) {
            if ($this->isPassportScopeMiddleware($middleware)) {
                Arr::set($documentation, 'security', [
                    'OAuth2'        =>  $middleware->parameters()
                ]);
            }
            if ($this->isSanctumScopeMiddleware($middleware)) {
                $flows = array_map(function(array $data): array {
                    return [];
                }, $this->securityDefinitions);
                if (!empty($flows)) {
                    Arr::set($documentation, 'security', [$flows]);
                }
            }
        }
    }

    /**
     * Retrieve form rules
     * @param ReflectionMethod $instance
     * @return array
     */
    private function retrieveFormRules(ReflectionMethod $instance): array {
        $parameters = $instance->getParameters();

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            if ($type) {
                if (method_exists($type, 'getName')) {
                    $typeClass = $type->getName();
                    if (is_subclass_of($typeClass, FormRequest::class)) {
                        return (new $typeClass)->rules();
                    }
                }
            }
        }
        return [];
    }

    /**
     * Get middleware resolver class
     * @param string $middleware
     * @return string|null
     */
    private function getMiddlewareResolver(string $middleware): ?string {
        $middlewareMap = app('router')->getMiddleware();
        return $middlewareMap[$middleware] ?? null;
    }

    /**
     * Check if Laravel Passport is in use
     * @param Middleware $middleware
     * @return bool
     */
    private function isPassportScopeMiddleware(Middleware $middleware): bool {
        $resolver = $this->getMiddlewareResolver($middleware->name());
        return
            $resolver === 'Laravel\Passport\Http\Middleware\CheckScopes'
            || $resolver === 'Laravel\Passport\Http\Middleware\CheckForAnyScope';
    }

    /**
     * Check if Laravel Sanctum is in use
     * @param Middleware $middleware
     * @return bool
     */
    private function isSanctumScopeMiddleware(Middleware $middleware): bool {
        return in_array('sanctum', $middleware->parameters());
    }

}
