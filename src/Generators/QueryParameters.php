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
     * Parameters location
     * @var string
     */
    protected string $location = 'query';

    /**
     * QueryParameters constructor.
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
     * @inhereritDoc
     */
    public function parameters(): array {
        $parameters = $this->extractParametersFor('query');
        $arrayTypes = [];

        foreach ($this->rules as $parameter => $rule) {
            $parameterRules = $this->splitRules($rule);
            $enums = $this->getEnumValues($parameterRules);
            $type = $this->getParameterType($parameterRules);
            $default = $this->getDefaultValue($parameterRules);

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

            if (\count($enums) > 0) {
                Arr::set($parameterObject, 'enum', $enums);
            } else {
                Arr::set($parameterObject, 'schema.type', $type);
            }

            if ($default) {
                settype($default, $type);
                Arr::set($parameterObject, 'schema.default', $default);
            }

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

}
