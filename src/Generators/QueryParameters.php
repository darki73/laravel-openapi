<?php namespace FreedomCore\OpenAPI\Generators;

use Illuminate\Support\Arr;
use ReflectionMethod;
use FreedomCore\OpenAPI\Traits\Generators\GeneratesFromRules;

/**
 * Class QueryParameters
 * @package FreedomCore\OpenAPI\Generators
 */
class QueryParameters extends AbstractGenerator {
    use GeneratesFromRules;

    /**
     * Rules array
     * @var array
     */
    private array $rules;

    /**
     * Attributes array
     * @var array
     */
    private array $attributes;

    /**
     * Parameters location
     * @var string
     */
    protected string $location = 'query';

    /**
     * QueryParameters constructor.
     * @param string $uri
     * @param array $rules
     * @param array $attributes
     * @param ReflectionMethod $methodInstance
     */
    public function __construct(string $uri, array $rules, array $attributes, ReflectionMethod $methodInstance) {
        $this->uri = $uri;
        $this->rules = $rules;
        $this->attributes = $attributes;
        $this->method = $methodInstance;
    }

    /**
     * @inhereritDoc
     */
    public function parameters(): array {
        $parameters = $this->extractParametersFor('query');
        $arrayTypes = [];

        foreach ($this->rules as $parameter => $rule) {
            $parameterRules = $this->splitRules($rule);
            $enums = $this->getEnumValues($parameterRules);
            $type = $this->getParameterType($parameterRules);

            if ($this->isArrayParameter($parameter)) {
                $key = $this->getArrayKey($parameter);
                $arrayTypes[$key] = $type;
                continue;
            }

            $parameterObject = [
                'in'            =>  $this->parameterLocation(),
                'name'          =>  $parameter,
                'description'   =>  '',
                'required'      =>  $this->isParameterRequired($parameterRules)
            ];

            Arr::set($parameterObject, 'schema.type', $type);
            if (\count($enums) > 0) {
                Arr::set($parameterObject, 'schema.enum', $enums);
            }

            $this->updateDefaultValues($parameterObject, $parameter);
            $this->updateRangeValues($parameterObject, $parameter);

            if ($type === 'array') {
                Arr::set($parameterObject, 'items', [
                    'type'  =>  'string'
                ]);
            }
            Arr::set($parameters, $parameter, $parameterObject);
        }
        $parameters = $this->addArrayTypes($parameters, $arrayTypes);

        return array_values($parameters);
    }

    /**
     * Add array types
     * @param array $parameters
     * @param array $arrayTypes
     * @return array
     */
    protected function addArrayTypes(array $parameters, array $arrayTypes): array {
        foreach ($arrayTypes as $key => $type) {
            if (!isset($parameters[$key])) {
                $parameters[$key] = [
                    'name'          =>  $key,
                    'in'            =>  $this->parameterLocation(),
                    'type'          =>  'array',
                    'required'      =>  false,
                    'description'   =>  '',
                    'items'         =>  [
                        'type'      =>  $type
                    ]
                ];
            } else {
                $parameters[$key]['type'] = 'array';
                $parameters[$key]['items']['type'] = $type;
            }
        }
        return $parameters;
    }

    /**
     * Update parameter default value
     * @param array $parameterObject
     * @param string $parameter
     */
    private function updateDefaultValues(array & $parameterObject, string $parameter): void {
        if (!empty($this->attributes)) {
            if (Arr::has($this->attributes, 'default')) {
                $defaults = Arr::get($this->attributes, 'default');
                if (Arr::has($defaults, $parameter)) {
                    $parameterData = Arr::get($defaults, $parameter);
                    Arr::set($parameterObject, 'schema.type', Arr::get($parameterData, 'type'));
                    Arr::set($parameterObject, 'schema.default', Arr::get($parameterData, 'default'));
                }
            }
        }
    }

    /**
     * Update range values
     * @param array $parameterObject
     * @param string $parameter
     */
    private function updateRangeValues(array & $parameterObject, string $parameter): void {
        if (!empty($this->attributes)) {
            $ranges = ['minimum', 'maximum', 'minLength', 'maxLength'];
            foreach ($ranges as $range) {
                if (Arr::has($this->attributes, $range)) {
                    $rangeData = Arr::get($this->attributes, $range);
                    if (Arr::has($rangeData, $parameter)) {
                        $parameterData = Arr::get($rangeData, $parameter);
                        Arr::set($parameterObject, 'schema.type', Arr::get($parameterData, 'type'));
                        Arr::set($parameterObject, 'schema.' . $range, Arr::get($parameterData, 'value'));
                    }
                }
            }
        }
    }

}
