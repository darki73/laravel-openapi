<?php namespace FreedomCore\OpenAPI\Generators;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionMethod;
use FreedomCore\OpenAPI\Traits\Generators\GeneratesFromRules;

/**
 * Class BodyParameters
 * @package FreedomCore\OpenAPI\Generators
 */
class BodyParameters extends AbstractGenerator {
    use GeneratesFromRules;

    /**
     * Rules array
     * @var array
     */
    private array $rules;

    /**
     * Parameters location
     * @var string
     */
    protected string $location = 'body';

    /**
     * BodyParameters constructor.
     * @param string $uri
     * @param array $rules
     * @param ReflectionMethod $methodInstance
     */
    public function __construct(string $uri, array $rules, ReflectionMethod $methodInstance) {
        $this->uri = $uri;
        $this->rules = $rules;
        $this->method = $methodInstance;
    }

    /**
     * @inheritDoc
     */
    public function parameters(): array {
        $properties = [];
        $required = [];
        $schema = [];

        foreach ($this->rules as $parameter => $rule) {
            $parameterRules = $this->splitRules($rule);

            $nameTokens = explode('.', $parameter);
            $this->addToProperties($properties,  $nameTokens, $parameterRules);

            if ($this->isParameterRequired($parameterRules)) {
                $required[] = $parameter;
            }
        }

        if (count($required) > 0) {
            Arr::set($schema, 'required', $required);
        }

        Arr::set($schema, 'properties', $properties);

        if (empty($properties)) {
            return [];
        }

        return [
            'content'               =>  [
                'application/json'  =>  [
                    'schema'        =>  $schema
                ]
            ]
        ];
    }

    /**
     * Add data to properties array
     * @param array $properties
     * @param array $nameTokens
     * @param array $rules
     */
    protected function addToProperties(array & $properties, array $nameTokens, array $rules): void {
        if (\count($nameTokens) === 0) {
            return;
        }

        $name = array_shift($nameTokens);

        if (!empty($nameTokens)) {
            $type = $this->getNestedParameterType($nameTokens);
        } else {
            $type = $this->getParameterType($rules);
        }

        if ($name === '*') {
            $name = 0;
        }

        if (!Arr::has($properties, $name)) {
            $propertyObject = $this->createNewPropertyObject($type, $rules);
            Arr::set($properties, $name, $propertyObject);
        } else {
            Arr::set($properties, $name . '.type', $type);
        }

        foreach ($rules as $rule) {
            if (Str::startsWith($rule, 'min')) {
                Arr::set($properties, $name . '.minimum', (integer) trim(str_replace('min:', '', $rule)));
            }
            if (Str::startsWith($rule, 'max')) {
                Arr::set($properties, $name . '.maximum', (integer) trim(str_replace('max:', '', $rule)));
            }
        }


        if ($type === 'array') {
            $this->addToProperties($properties[$name]['items'], $nameTokens, $rules);
        } else if ($type === 'object') {
            $this->addToProperties($properties[$name]['properties'], $nameTokens, $rules);
        }
    }

    /**
     * Get nested parameter type
     * @param array $nameTokens
     * @return string
     */
    protected function getNestedParameterType(array $nameTokens): string {
        if (current($nameTokens) === '*') {
            return 'array';
        }
        return 'object';
    }

    /**
     * Create new property object
     * @param string $type
     * @param array $rules
     * @return string[]
     */
    protected function createNewPropertyObject(string $type, array $rules): array {
        $propertyObject = [
            'type'      =>  $type,
        ];

        if ($enums = $this->getEnumValues($rules)) {
            Arr::set($propertyObject, 'enum', $enums);
        }

        if ($type === 'array') {
            Arr::set($propertyObject, 'items', []);
        } else if ($type === 'object') {
            Arr::set($propertyObject, 'properties', []);
        }

        return $propertyObject;
    }

}
