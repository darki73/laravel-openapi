<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class NoContent
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class NoContent extends Response {

    /**
     * NoContent constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(204, $description);
    }

}
