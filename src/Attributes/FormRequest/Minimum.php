<?php namespace FreedomCore\OpenAPI\Attributes\FormRequest;

use Attribute;

/**
 * Class Minimum
 * @package FreedomCore\OpenAPI\Attributes\FormRequest
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Minimum {

    /**
     * Attribute we are working with
     * @var string
     */
    public string $attribute;

    /**
     * Attribute minimum value
     * @var int
     */
    public int $minimum;

    /**
     * Attribute default value
     * @var int
     */
    public int $default;

    /**
     * Defaults constructor.
     * @param string $attribute
     * @param int $minimum
     * @param int|null $default
     */
    public function __construct(string $attribute, int $minimum, ?int $default = null) {
        $this->attribute = $attribute;
        $this->minimum = $minimum;
        $this->default = $default ?: $minimum;
    }

    public function toArray(): array {
        return [
            'minimum'                   =>  [
                $this->attribute        =>  [
                    'type'              =>  'integer',
                    'default'           =>  $this->default,
                    'value'             =>  $this->minimum
                ]
            ]
        ];
    }

}
