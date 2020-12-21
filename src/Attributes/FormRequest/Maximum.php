<?php namespace FreedomCore\OpenAPI\Attributes\FormRequest;

use Attribute;

/**
 * Class Maximum
 * @package FreedomCore\OpenAPI\Attributes\FormRequest
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Maximum {

    /**
     * Attribute we are working with
     * @var string
     */
    public string $attribute;

    /**
     * Attribute maximum value
     * @var int
     */
    public int $maximum;

    /**
     * Defaults constructor.
     * @param string $attribute
     * @param int $maximum
     */
    public function __construct(string $attribute, int $maximum) {
        $this->attribute = $attribute;
        $this->maximum = $maximum;
    }

    public function toArray(): array {
        return [
            'maximum'                   =>  [
                $this->attribute        =>  [
                    'type'              =>  'integer',
                    'value'             =>  $this->maximum
                ]
            ]
        ];
    }

}
