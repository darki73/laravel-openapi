<?php namespace FreedomCore\OpenAPI\Builder;

/**
 * Class SpecificationComponents
 * @package FreedomCore\OpenAPI\Builder
 */
class SpecificationComponents {

    /**
     * List of security definitions
     * @var array
     */
    private array $securityDefinitions;

    /**
     * SpecificationComponents constructor.
     * @param array $securityDefinitions
     */
    public function __construct(array $securityDefinitions = []) {
        $this->securityDefinitions = $securityDefinitions;
    }

    /**
     * Convert class to array
     * @return array
     */
    public function toArray(): array {
        return [
            'securitySchemes'       =>  $this->securityDefinitions(),
        ];
    }

    /**
     * Get list of security definitions
     * @return array
     */
    public function securityDefinitions(): array {
        return $this->securityDefinitions;
    }

}
