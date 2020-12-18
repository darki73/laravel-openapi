<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class Created
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Created extends Response {

    /**
     * Created constructor.
     * @param string $description
     */
    public function __construct(string $description = '') {
        parent::__construct(201, $description);
    }

}
