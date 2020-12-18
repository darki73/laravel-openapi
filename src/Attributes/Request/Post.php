<?php namespace FreedomCore\OpenAPI\Attributes\Request;

use Attribute;

/**
 * Class Post
 * @package FreedomCore\OpenAPI\Attributes\Request
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Post extends Request {

    /**
     * Post constructor.
     * @param string $description
     * @param bool $deprecated
     */
    public function __construct(string $description = '', bool $deprecated = false) {
        parent::__construct('POST', $description, $deprecated);
    }

}
