<?php namespace FreedomCore\OpenAPI\Attributes\Request;

use Attribute;

/**
 * Class Request
 * @package FreedomCore\OpenAPI\Attributes\Request
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Request {

    /**
     * Method name
     * @var string
     */
    public string $method;

    /**
     * Method description
     * @var string
     */
    public string $description;

    /**
     * Indicates whether this method is deprecated
     * @var bool
     */
    public bool $deprecated;

    /**
     * Request constructor.
     * @param string $method
     * @param string $description
     * @param bool $deprecated
     */
    public function __construct(string $method, string $description = '', bool $deprecated = false) {
        $this->method = $method;
        $this->description = $description;
        $this->deprecated = $deprecated;
    }

}
