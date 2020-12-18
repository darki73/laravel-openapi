<?php namespace FreedomCore\OpenAPI\Attributes\Request;

use Attribute;

/**
 * Class Delete
 * @package FreedomCore\OpenAPI\Attributes\Request
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Delete extends Request {

    /**
     * Delete constructor.
     * @param string $description
     * @param bool $deprecated
     */
    public function __construct(string $description = '', bool $deprecated = false) {
        parent::__construct('DELETE', $description, $deprecated);
    }

}
