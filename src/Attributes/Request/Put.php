<?php namespace FreedomCore\OpenAPI\Attributes\Request;

use Attribute;

/**
 * Class Put
 * @package FreedomCore\OpenAPI\Attributes\Request
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Put extends Request {

    /**
     * Put constructor.
     * @param string $description
     * @param bool $deprecated
     */
    public function __construct(string $description = '', bool $deprecated = false) {
        parent::__construct('PUT', $description, $deprecated);
    }

}
