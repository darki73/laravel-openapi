<?php namespace FreedomCore\OpenAPI\Attributes\Request;

use Attribute;

/**
 * Class Patch
 * @package FreedomCore\OpenAPI\Attributes\Request
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Patch extends Request {

    /**
     * Patch constructor.
     * @param string $description
     * @param bool $deprecated
     */
    public function __construct(string $description = '', bool $deprecated = false) {
        parent::__construct('PATCH', $description, $deprecated);
    }

}
