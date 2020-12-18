<?php


use Illuminate\Support\Facades\Route;
use FreedomCore\OpenAPI\Http\Controllers\OpenAPIController;

Route::prefix(ltrim(config('openapi.api.path', '/documentation'), '/'))->group(static function () {
    Route::get('', [OpenAPIController::class, 'api'])->name('openapi.ui');
    Route::get('content', [OpenAPIController::class, 'documentation'])->name('openapi.content');
    Route::get('content.json', [OpenAPIController::class, 'documentationJson'])->name('openapi.content.json');
    Route::get('content.yaml', [OpenAPIController::class, 'documentationYaml'])->name('openapi.content.yaml');
});
