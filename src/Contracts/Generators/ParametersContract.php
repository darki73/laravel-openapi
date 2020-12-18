<?php namespace FreedomCore\OpenAPI\Contracts\Generators;

/**
 * Interface ParametersContract
 * @package FreedomCore\OpenAPI\Contracts\Generators
 */
interface ParametersContract {

    /**
     * Get list of parameters
     * @return array
     */
    public function parameters(): array;

    /**
     * Get parameter location
     * @return string
     */
    public function parameterLocation(): string;

}
