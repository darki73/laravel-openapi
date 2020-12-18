<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class Unauthorized
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Unauthorized extends Response {

    /**
     * Unauthorized constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(401, $description);
    }

}
