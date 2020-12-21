<?php namespace FreedomCore\OpenAPI\Attributes\FormRequest;

use Attribute;

/**
 * Class MaxLength
 * @package FreedomCore\OpenAPI\Attributes\FormRequest
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class MaxLength {

    /**
     * Attribute we are working with
     * @var string
     */
    public string $attribute;

    /**
     * Attribute maxLength value
     * @var int
     */
    public int $maxLength;

    /**
     * Defaults constructor.
     * @param string $attribute
     * @param int $maxLength
     */
    public function __construct(string $attribute, int $maxLength) {
        $this->attribute = $attribute;
        $this->maxLength = $maxLength;
    }

    public function toArray(): array {
        return [
            'maxLength'                 =>  [
                $this->attribute        =>  [
                    'value'             =>  $this->maxLength,
                    'type'              =>  'string'
                ]
            ]
        ];
    }

}
