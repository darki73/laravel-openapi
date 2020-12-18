<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class InternalServerError
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class InternalServerError extends Response {

    /**
     * InternalServerError constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(500, $description);
    }

}
