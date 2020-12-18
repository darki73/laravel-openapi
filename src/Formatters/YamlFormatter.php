<?php namespace FreedomCore\OpenAPI\Formatters;

use FreedomCore\OpenAPI\Exceptions\ExtensionNotLoaded;

/**
 * Class YamlFormatter
 * @package FreedomCore\OpenAPI\Formatters
 */
class YamlFormatter extends AbstractFormatter {

    /**
     * @inheritDoc
     * @return string
     * @throws ExtensionNotLoaded
     */
    public function format(): string {
        if (!extension_loaded('yaml')) {
            throw new ExtensionNotLoaded('YAML extends must be loaded to use the `yaml` output format');
        }
        return yaml_emit($this->documentation);
    }

}
