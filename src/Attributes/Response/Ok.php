<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class Ok
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Ok extends Response {

    /**
     * Ok constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(200, $description);
    }

}
