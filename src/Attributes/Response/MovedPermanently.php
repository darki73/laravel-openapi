<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class MovedPermanently
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class MovedPermanently extends Response {

    /**
     * MovedPermanently constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(301, $description);
    }

}
