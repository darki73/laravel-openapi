<?php namespace FreedomCore\OpenAPI\Generators;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use ReflectionMethod;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use FreedomCore\OpenAPI\Contracts\Generators\ParametersContract;

/**
 * Class AbstractGenerator
 * @package FreedomCore\OpenAPI\Generators
 */
abstract class AbstractGenerator implements ParametersContract {

    /**
     * Path uri
     * @var string
     */
    protected string $uri;

    /**
     * Method instance
     * @var ReflectionMethod
     */
    protected ReflectionMethod $method;

    /**
     * Parameters location
     * @var string
     */
    protected string $location;

    /**
     * @inheritDoc
     * @return string
     */
    public function parameterLocation(): string {
        return $this->location;
    }

    /**
     * Extract method parameters
     * @return array
     */
    protected function extractMethodParameters(): array {
        $methodParameters = $this->method->getParameters();
        $parameters = [];

        foreach ($methodParameters as $parameter) {
            $parameterType = $parameter->getType();
            if (method_exists($parameterType, 'getName')) {
                $parameterType = (string) $parameterType->getName();
            }
            if ($parameterType !== \Illuminate\Http\Request::class && !is_subclass_of($parameterType, FormRequest::class)) {
                $default = null;
                try {
                    $default = $parameter->getDefaultValue();
                } catch (Exception) {}
                $parameterData = [
                    'name'      =>  $parameter->getName(),
                    'in'        =>  $this->parameterLocation(),
                    'required'  =>  !$parameter->allowsNull(),
                    'schema'    =>  []
                ];
                if (Str::contains($parameterType, '|')) {
                    $parameterType = explode('|', $parameterType);
                }
                if (is_string($parameterType)) {
                    Arr::set($parameterData, 'schema.type', $this->formatType($parameterType));
                } else {
                    foreach ($parameterType as $type) {
                        $parameterData['schema']['anyOf'][] = [
                            'type'      =>  $this->formatType($type)
                        ];
                    }
                }

                if ($default) {
                    $required = false;
                    Arr::set($parameterData, 'required', $required);
                    Arr::set($parameterData, 'schema.default', $default);
                }
                $parameters[$parameter->getName()] = $parameterData;
            }
        }

        return $parameters;
    }

    /**
     * Extract path parameters
     * @return array
     */
    protected function extractPathParameters(): array {
        $parameters = [];
        $pathVariables = $this->getVariablesFromUri();

        foreach ($pathVariables as $variable) {
            $parameters[strip_optional_char($variable)] = [
                'name'          =>  strip_optional_char($variable),
                'in'            =>  $this->parameterLocation(),
                'required'      =>  true,
                'description'   =>  '',
                'schema'        =>  [
                    'type'      =>  'string',
                ]
            ];
        }

        return $parameters;
    }

    /**
     * Extract parameters for specific generator
     * @param string $type
     * @return array
     */
    protected function extractParametersFor(string $type): array {
        $methodParameters = $this->extractMethodParameters();
        $pathParameters = $this->extractPathParameters();
        switch ($type) {
            case 'query':
                foreach ($methodParameters as $parameter => $data) {
                    if (Arr::has($pathParameters, $parameter)) {
                        unset($methodParameters[$parameter]);
                    }
                }
                return array_values($methodParameters);
            case 'path':
                foreach ($pathParameters as $parameter => $data) {
                    if (Arr::has($methodParameters, $parameter)) {
                        $parameterData = Arr::get($methodParameters, $parameter);
                        Arr::set($parameterData, 'required', true);
                        Arr::set($pathParameters, $parameter, $parameterData);
                    }
                }
                return array_values($pathParameters);
        }
        return [];
    }

    /**
     * Format type
     * @param string $type
     * @return string
     */
    protected function formatType(string $type): string {
        return match($type) {
            'int'           =>  'integer',
            'bool'          =>  'boolean',
            default         =>  $type,
        };
    }

    /**
     * Get path variables from URI
     * @return array
     */
    protected function getVariablesFromUri(): array {
        preg_match_all('/{(\w+\??)}/', $this->uri, $pathVariables);
        return $pathVariables[1];
    }

    /**
     * Check whether this is a required variable
     * @param string $pathVariable
     * @return bool
     */
    protected function isPathVariableRequired(string $pathVariable): bool {
        return !Str::contains($pathVariable, '?');
    }

}
