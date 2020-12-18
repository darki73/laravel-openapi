<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class Forbidden
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Forbidden extends Response {

    /**
     * Forbidden constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(403, $description);
    }

}
