<?php namespace FreedomCore\OpenAPI\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use FreedomCore\OpenAPI\OpenAPI;
use FreedomCore\OpenAPI\Formatter;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Routing\Controller as BaseController;
use FreedomCore\OpenAPI\Exceptions\ExtensionNotLoaded;
use Illuminate\Support\Facades\Response as ResponseFacade;
use FreedomCore\OpenAPI\Exceptions\InvalidFormatException;
use FreedomCore\OpenAPI\Exceptions\InvalidAuthenticationFlow;

/**
 * Class OpenAPIController
 * @package FreedomCore\OpenAPI\Http\Controllers
 */
class OpenAPIController extends BaseController {

    /**
     * Configuration repository
     * @var Repository
     */
    protected Repository $configuration;

    /**
     * OpenAPIController constructor.
     * @param Repository $configuration
     */
    public function __construct(Repository $configuration) {
        $this->configuration = $configuration;
    }

    /**
     * Return documentation content
     * @param Request $request
     * @return Response
     * @throws ExtensionNotLoaded|InvalidFormatException
     */
    public function documentation(Request $request): Response {
        if (config('openapi.generated', false)) {
            $documentation = (new OpenAPI)->documentation();
            return ResponseFacade::make((new Formatter($documentation))->setFormat('json')->format(), 200, [
                'Content-Type' => 'application/json',
            ]);
        }
        $documentation = openapi_resolve_documentation_file_path();
        if (strlen($documentation) === 0) {
            abort(404, sprintf('Please generate documentation first, then access this page'));
        }
        $content = File::get($documentation);
        $yaml = Str::endsWith('yaml', pathinfo($documentation, PATHINFO_EXTENSION));
        if ($yaml) {
            return ResponseFacade::make($content, 200, [
                'Content-Type' => 'application/yaml',
                'Content-Disposition' => 'inline',
            ]);
        }
        return ResponseFacade::make($content, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Get documentation in JSON format
     * @param Request $request
     * @return Response
     * @throws ExtensionNotLoaded|InvalidFormatException
     */
    public function documentationJson(Request $request): Response {
        $documentation = (new OpenAPI)->documentation();
        return ResponseFacade::make((new Formatter($documentation))->setFormat('json')->format(), 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Get documentation in YAML format
     * @param Request $request
     * @return Response
     * @throws ExtensionNotLoaded|InvalidFormatException
     */
    public function documentationYaml(Request $request): Response {
        $documentation = (new OpenAPI)->documentation();
        return ResponseFacade::make((new Formatter($documentation))->setFormat('yaml')->format(), 200, [
            'Content-Type' => 'application/x-yaml',
        ]);
    }

    /**
     * Render OpenAPI UI page
     * @param Request $request
     * @return Response
     */
    public function api(Request $request): Response {
        $url = config('openapi.api.host');
        if (!Str::startsWith($url, 'http://') && !Str::startsWith($url, 'https://')) {
            $schema = openapi_is_connection_secure() ? 'https://' : 'http://';
            $url = $schema . $url;
        }
        return ResponseFacade::make(view('openapi::index', [
            'secure'            =>  openapi_is_connection_secure(),
            'urlToDocs'         =>  $url . config('openapi.api.path', '/documentation') . '/content'
        ]), 200);
    }

}
