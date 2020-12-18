<?php namespace FreedomCore\OpenAPI\Attributes\Request;

use Attribute;

/**
 * Class Get
 * @package FreedomCore\OpenAPI\Attributes\Request
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Get extends Request {

    /**
     * Get constructor.
     * @param string $description
     * @param bool $deprecated
     */
    public function __construct(string $description = '', bool $deprecated = false) {
        parent::__construct('GET', $description, $deprecated);
    }

}
