<?php namespace FreedomCore\OpenAPI\Attributes;

use Attribute;

/**
 * Class Controller
 * @package FreedomCore\OpenAPI\Attributes
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Controller {

    /**
     * Controller name
     * @var string
     */
    public string $name;

    /**
     * Controller description
     * @var string
     */
    public string $description;

    /**
     * Controller external documentation information array
     * @var array
     */
    public array $externalDocs;

    /**
     * Controller constructor.
     * @param string $name
     * @param string $description
     * @param array $externalDocs
     */
    public function __construct(string $name, string $description = '', array $externalDocs = []) {
        $this->name = $name;
        $this->description = $description;
        $this->externalDocs = $externalDocs;
    }

}
