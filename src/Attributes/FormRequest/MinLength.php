<?php namespace FreedomCore\OpenAPI\Attributes\FormRequest;

use Attribute;

/**
 * Class MinLength
 * @package FreedomCore\OpenAPI\Attributes\FormRequest
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class MinLength {

    /**
     * Attribute we are working with
     * @var string
     */
    public string $attribute;

    /**
     * Attribute minLength value
     * @var int
     */
    public int $minLength;

    /**
     * Defaults constructor.
     * @param string $attribute
     * @param int $minLength
     */
    public function __construct(string $attribute, int $minLength) {
        $this->attribute = $attribute;
        $this->minLength = $minLength;
    }

    public function toArray(): array {
        return [
            'minLength'                 =>  [
                $this->attribute        =>  [
                    'value'             =>  $this->minLength,
                    'type'              =>  'string'
                ]
            ]
        ];
    }

}
