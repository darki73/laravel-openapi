<?php namespace FreedomCore\OpenAPI\Attributes\FormRequest;

use Attribute;

/**
 * Class Defaults
 * @package FreedomCore\OpenAPI\Attributes\FormRequest
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Defaults {

    /**
     * Attribute we are working with
     * @var string
     */
    public string $attribute;

    /**
     * Type of variable
     * @var string
     */
    public string $type;

    /**
     * Default variable type
     * @var string|int
     */
    public string|int $default;

    /**
     * Defaults constructor.
     * @param string $attribute
     * @param string|int $default
     * @param string $type
     */
    public function __construct(string $attribute, string|int $default, string $type = 'string') {
        $this->attribute = $attribute;
        $this->type = $type;
        $this->default = $default;
    }

    public function toArray(): array {
        return [
            'default'                   =>  [
                $this->attribute        =>  [
                    'type'              =>  $this->type,
                    'default'           =>  $this->default
                ]
            ]
        ];
    }

}
