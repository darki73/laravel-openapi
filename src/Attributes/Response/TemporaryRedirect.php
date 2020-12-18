<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class TemporaryRedirect
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class TemporaryRedirect extends Response {

    /**
     * TemporaryRedirect constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(307, $description);
    }

}
