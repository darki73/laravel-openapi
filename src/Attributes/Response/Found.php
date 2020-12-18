<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class Found
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Found extends Response {

    /**
     * Found constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(302, $description);
    }

}
