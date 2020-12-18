<?php namespace FreedomCore\OpenAPI\Commands;

use Illuminate\Console\Command;
use FreedomCore\OpenAPI\OpenAPI;
use FreedomCore\OpenAPI\Formatter;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Config\Repository;
use FreedomCore\OpenAPI\Exceptions\ExtensionNotLoaded;
use FreedomCore\OpenAPI\Exceptions\InvalidFormatException;

/**
 * Class GenerateOpenAPIDocumentation
 * @package FreedomCore\OpenAPI\Commands
 */
class GenerateOpenAPIDocumentation extends Command {

    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'openapi:generate
                            {--format=json : The format of the output, current options are json and yaml}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Generate OpenAPI documentation for application';

    /**
     * Config repository instance
     * @var Repository
     */
    protected Repository $configuration;

    /**
     * GenerateOpenAPIDocumentation constructor.
     * @param Repository $configuration
     */
    public function __construct(Repository $configuration) {
        $this->configuration = $configuration;
        parent::__construct();
    }

    /**
     * @inheritDoc
     * @throws InvalidFormatException|ExtensionNotLoaded
     */
    public function handle(): void {
        $format = $this->option('format');

        $documentation = (new OpenAPI)->documentation();
        $formattedDocs = (new Formatter($documentation))->setFormat($format)->format();

        $storagePath = $this->configuration->get('openapi.storage');
        File::isDirectory($storagePath) or File::makeDirectory($storagePath, 0777, true, true);
        $file = implode(DIRECTORY_SEPARATOR, [$storagePath, 'openapi.' . $format]);
        file_put_contents($file, $formattedDocs);
    }
}
