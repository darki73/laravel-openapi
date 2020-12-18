<?php namespace FreedomCore\Swagger\Attributes\Response;

use Attribute;

/**
 * Class NotImplemented
 * @package FreedomCore\Swagger\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class NotImplemented extends Response {

    /**
     * NotImplemented constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(510, $description);
    }

}
