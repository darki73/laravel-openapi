<?php namespace FreedomCore\OpenAPI\Builder;

use Illuminate\Support\Arr;

/**
 * Class SpecificationBase
 * @package FreedomCore\OpenAPI\Builder
 */
class SpecificationBase {

    /**
     * API title
     * @var string
     */
    private string $title;

    /**
     * API description
     * @var string
     */
    private string $description;

    /**
     * API terms of service URL
     * @var string|null
     */
    private ?string $termsOfService;

    /**
     * API contact information
     * @var array|null
     */
    private ?array $contact;

    /**
     * API license information
     * @var array|null
     */
    private ?array $license;

    /**
     * API version
     * @var string
     */
    private string $version;

    /**
     * SpecificationBase constructor.
     */
    public function __construct() {
        $this
            ->extractBaseInformation()
            ->extractContactInformation()
            ->extractLicenseInformation();
    }

    /**
     * Convert class variables to array
     * @return array
     */
    public function toArray(): array {
        $specificationBase = [
            'openapi'           =>  '3.0.0',
            'info'              =>  [
                'title'         =>  $this->title(),
                'description'   =>  $this->description(),
                'version'       =>  $this->version()
            ]
        ];

        if ($this->tos()) {
            Arr::set($specificationBase, 'info.termsOfService', $this->tos());
        }

        if ($this->contact()) {
            Arr::set($specificationBase, 'info.contact', $this->contact());
        }

        if ($this->license()) {
            Arr::set($specificationBase, 'info.license', $this->license());
        }

        return $specificationBase;
    }

    /**
     * Get API title
     * @return string
     */
    public function title(): string {
        return $this->title;
    }

    /**
     * Get API description
     * @return string
     */
    public function description(): string {
        return $this->description;
    }

    /**
     * Get API Terms of Service URL
     * @return string|null
     */
    public function tos(): ?string {
        return $this->termsOfService;
    }

    /**
     * Get API contact information
     * @return array|null
     */
    public function contact(): ?array {
        return $this->contact;
    }

    /**
     * Get API license information
     * @return array|null
     */
    public function license(): ?array {
        return $this->license;
    }

    /**
     * Get API version
     * @return string
     */
    public function version(): string {
        return $this->version;
    }

    /**
     * Extract base information from configuration
     * @return SpecificationBase
     */
    private function extractBaseInformation(): SpecificationBase {
        $baseInformation = config('openapi.api');
        $this->title = Arr::get($baseInformation, 'title');
        $this->description = Arr::get($baseInformation, 'description');
        $this->version = Arr::get($baseInformation, 'version');
        $this->termsOfService = Arr::get($baseInformation, 'tos');
        return $this;
    }

    /**
     * Extract contact information from configuration
     * @return SpecificationBase
     */
    private function extractContactInformation(): SpecificationBase {
        $contactInformation = Arr::only(config('openapi.contact'), ['title', 'email', 'url']);
        $varCount = count($contactInformation);
        $nullCount = 0;

        foreach ($contactInformation as [$key, $value]) {
            if ($value === null) {
                $nullCount++;
                unset($contactInformation[$key]);
            }
        }

        $this->contact = $varCount !== $nullCount ? $contactInformation : null;
        return $this;
    }

    /**
     * Extract license information from configuration
     * @return SpecificationBase
     */
    private function extractLicenseInformation(): SpecificationBase {
        $licenseInformation = Arr::only(config('openapi.license'), ['title', 'url']);
        $varCount = count($licenseInformation);
        $nullCount = 0;

        foreach ($licenseInformation as [$key, $value]) {
            if ($value === null) {
                $nullCount++;
                unset($licenseInformation[$key]);
            }
        }

        $this->license = $varCount !== $nullCount ? $licenseInformation : null;
        return $this;
    }

}
