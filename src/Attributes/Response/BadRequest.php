<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class BadRequest
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class BadRequest extends Response {

    /**
     * BadRequest constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(400, $description);
    }

}
