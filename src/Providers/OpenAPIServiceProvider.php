<?php namespace FreedomCore\OpenAPI\Providers;

use Illuminate\Support\ServiceProvider;
use FreedomCore\OpenAPI\Commands\GenerateOpenAPIDocumentation;

/**
 * Class OpenAPIServiceProvider
 * @package FreedomCore\OpenAPI\Providers
 */
class OpenAPIServiceProvider extends ServiceProvider {

    /**
     * @inheritDoc
     * @return void
     */
    public function boot(): void {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateOpenAPIDocumentation::class
            ]);
        }
        $this
            ->publishConfiguration()
            ->publishViews()
            ->publishTranslations()
            ->loadRoutes()
            ->mergeConfiguration();
    }

    /**
     * Publish configuration file
     * @return OpenAPIServiceProvider
     */
    private function publishConfiguration(): OpenAPIServiceProvider {
        $source = __DIR__ . '/../../config/openapi.php';
        $this->publishes([
            $source     =>  config_path('openapi.php')
        ]);
        return $this;
    }

    /**
     * Publish views
     * @return OpenAPIServiceProvider
     */
    private function publishViews(): OpenAPIServiceProvider {
        $viewsPath = __DIR__ . '/../../resources/views';
        $this->loadViewsFrom($viewsPath, 'openapi');
        $this->publishes([
            $viewsPath      =>  config('openapi.views', base_path('resources/views/vendor/openapi'))
        ]);
        return $this;
    }

    /**
     * Publish translations
     * @return OpenAPIServiceProvider
     */
    private function publishTranslations(): OpenAPIServiceProvider {
        $translationsPath = __DIR__ . '/../../resources/lang';
        $this->publishes([
            $translationsPath       =>  config('openapi.translations', base_path('resources/lang/vendor/openapi'))
        ]);
        return $this;
    }

    /**
     * Load package routes
     * @return OpenAPIServiceProvider
     */
    private function loadRoutes(): OpenAPIServiceProvider {
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
        return $this;
    }

    /**
     * Merge configuration
     * @return OpenAPIServiceProvider
     */
    private function mergeConfiguration(): OpenAPIServiceProvider {
        $this->mergeConfigFrom(__DIR__ . '/../../config/openapi.php', 'openapi');
        return $this;
    }

}
