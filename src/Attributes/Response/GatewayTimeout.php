<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class GatewayTimeout
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class GatewayTimeout extends Response {

    /**
     * GatewayTimeout constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(504, $description);
    }

}
