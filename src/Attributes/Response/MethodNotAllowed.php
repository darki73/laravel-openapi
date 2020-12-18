<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class MethodNotAllowed
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class MethodNotAllowed extends Response {

    /**
     * MethodNotAllowed constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(405, $description);
    }

}
