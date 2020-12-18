<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class ServiceUnavailable
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class ServiceUnavailable extends Response {

    /**
     * ServiceUnavailable constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(503, $description);
    }

}
