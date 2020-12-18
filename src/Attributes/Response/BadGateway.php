<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class BadGateway
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class BadGateway extends Response {

    /**
     * BadGateway constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(502, $description);
    }

}
