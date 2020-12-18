<?php

if (!function_exists('strip_optional_char')) {
    function strip_optional_char(string $uri): string {
        return str_replace('?', '', $uri);
    }
}

if (!function_exists('openapi_is_connection_secure')) {
    function openapi_is_connection_secure(): bool {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            return true;
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            return true;
        }
        return false;
    }
}

if (!function_exists('openapi_resolve_documentation_file_path')) {
    function openapi_resolve_documentation_file_path(): string {
        $documentationFilePrefix = config('openapi.storage', storage_path('openapi')) . DIRECTORY_SEPARATOR . 'openapi.';
        $documentationFile = '';
        if (File::exists($documentationFilePrefix . 'json')) {
            $documentationFile = $documentationFilePrefix . 'json';
        } else {
            if (File::exists($documentationFilePrefix . 'yaml')) {
                $documentationFile = $documentationFilePrefix . 'yaml';
            }
        }
        return $documentationFile;
    }
}
