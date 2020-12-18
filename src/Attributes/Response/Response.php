<?php namespace FreedomCore\OpenAPI\Attributes\Response;

use Attribute;

/**
 * Class Response
 * @package FreedomCore\OpenAPI\Attributes\Response
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Response {

    /**
     * Response code
     * @var int
     */
    public int $code;

    /**
     * Response description
     * @var string
     */
    public string $description;

    /**
     * Response constructor.
     * @param int $code
     * @param string $description
     */
    public function __construct(int $code, string $description) {
        $this->code = $code;
        $this->description = $description;
    }

}
