<?php namespace FreedomCore\OpenAPI\Generators;

use ReflectionMethod;

/**
 * Class PathParameters
 * @package FreedomCore\OpenAPI\Generators
 */
class PathParameters extends AbstractGenerator {

    /**
     * Parameter location
     * @var string
     */
    protected string $location = 'path';

    /**
     * PathParameters constructor.
     * @param string $uri
     * @param ReflectionMethod $methodInstance
     */
    public function __construct(string $uri, ReflectionMethod $methodInstance) {
        $this->uri = $uri;
        $this->method = $methodInstance;
    }

    /**
     * @inhereritDoc
     */
    public function parameters(): array {
        return $this->extractParametersFor('path');
    }

}
