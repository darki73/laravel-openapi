<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class Accepted
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Accepted extends Response {

    /**
     * Accepted constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(202, $description);
    }

}
