<?php namespace FreedomCore\OpenAPI\Builder;

use Illuminate\Support\Arr;

/**
 * Class SpecificationServers
 * @package FreedomCore\OpenAPI\Builder
 */
class SpecificationServers {

    /**
     * List of servers for API
     * @var array
     */
    protected array $servers = [];

    /**
     * SpecificationServers constructor.
     */
    public function __construct() {
        $this->loadServers();
    }

    /**
     * Get list of servers for API
     * @return array
     */
    public function servers(): array {
        return $this->servers;
    }

    /**
     * Load list of servers for API
     * @return SpecificationServers
     */
    private function loadServers(): SpecificationServers {
        foreach (config('openapi.servers') as $server) {
            if (is_string($server)) {
                $this->servers[] = [
                    'url'           =>  $server,
                    'description'   =>  'Default Server'
                ];
            } else {
                $serverData = Arr::only($server, ['url', 'description', 'variables']);
                if (Arr::has($serverData, 'url')) {
                    if (Arr::has($serverData, 'variables')) {
                        $serverVariables = Arr::get($serverData, 'variables');
                        foreach ($serverVariables as $key => $value) {
                            $value = Arr::only($value,['enum', 'default', 'description']);
                            if (count($value) > 0) {
                                Arr::set($serverVariables, $key, $value);
                            } else {
                                unset($serverVariables[$key]);
                            }
                        }
                        Arr::set($serverData, 'variables', $serverVariables);
                    }
                    $this->servers[] = $serverData;
                }
            }
        }
        return $this;
    }

}
