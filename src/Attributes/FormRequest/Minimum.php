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
     * Defaults constructor.
     * @param string $attribute
     * @param int $minimum
     */
    public function __construct(string $attribute, int $minimum) {
        $this->attribute = $attribute;
        $this->minimum = $minimum;
    }

    public function toArray(): array {
        return [
            'minimum'                   =>  [
                $this->attribute        =>  [
                    'type'              =>  'integer',
                    'value'             =>  $this->minimum
                ]
            ]
        ];
    }

}
