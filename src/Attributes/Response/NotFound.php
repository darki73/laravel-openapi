<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class NotFound
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class NotFound extends Response {

    /**
     * NotFound constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(404, $description);
    }

}
