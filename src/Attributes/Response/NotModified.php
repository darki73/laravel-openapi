<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class NotModified
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class NotModified extends Response {

    /**
     * NotModified constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(304, $description);
    }

}
