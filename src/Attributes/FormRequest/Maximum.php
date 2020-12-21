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
     * Attribute default value
     * @var int
     */
    public int $default;

    /**
     * Defaults constructor.
     * @param string $attribute
     * @param int $maximum
     * @param int|null $default
     */
    public function __construct(string $attribute, int $maximum, ?int $default = null) {
        $this->attribute = $attribute;
        $this->maximum = $maximum;
        $this->default = $default ?: $maximum;
    }

    public function toArray(): array {
        return [
            'maximum'                   =>  [
                $this->attribute        =>  [
                    'type'              =>  'integer',
                    'default'           =>  $this->default,
                    'value'             =>  $this->maximum
                ]
            ]
        ];
    }

}
