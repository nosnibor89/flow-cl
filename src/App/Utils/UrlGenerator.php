<?php

namespace App\Utils;

/**
 * Generates Url with proper query params
 */
trait UrlGenerator
{
    /**
    *  @param $queryParams array This array should have key - values well defined
    */
    public function assembleUrl(string $baseUrl, array $queryParams): string
    {
        $assembledQueryParams = http_build_query($queryParams);
        return sprintf('%s?%s', $baseUrl, $assembledQueryParams);
    }
}
