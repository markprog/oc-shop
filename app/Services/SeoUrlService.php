<?php

namespace App\Services;

use App\Models\SeoUrl;
use Illuminate\Support\Facades\Cache;

class SeoUrlService
{
    /**
     * Resolve a URL keyword to its route query string.
     */
    public function resolve(string $keyword, int $languageId = 1, int $storeId = 0): ?string
    {
        return Cache::remember("seo_url:keyword:{$keyword}:{$languageId}:{$storeId}", 3600, function () use ($keyword, $languageId, $storeId) {
            return SeoUrl::where('keyword', $keyword)
                ->where('language_id', $languageId)
                ->where('store_id', $storeId)
                ->value('query');
        });
    }

    /**
     * Get the SEO keyword for a given query.
     */
    public function keyword(string $query, int $languageId = 1, int $storeId = 0): ?string
    {
        return Cache::remember("seo_url:query:{$query}:{$languageId}:{$storeId}", 3600, function () use ($query, $languageId, $storeId) {
            return SeoUrl::where('query', $query)
                ->where('language_id', $languageId)
                ->where('store_id', $storeId)
                ->value('keyword');
        });
    }

    /**
     * Generate a URL for a route with SEO keyword if available.
     */
    public function url(string $query, int $languageId = 1, int $storeId = 0): string
    {
        $keyword = $this->keyword($query, $languageId, $storeId);
        if ($keyword) {
            return url($keyword);
        }

        // Fallback to route-based URL
        [$route, $params] = $this->parseQuery($query);
        return route($route, $params);
    }

    private function parseQuery(string $query): array
    {
        parse_str($query, $params);

        if (isset($params['product_id'])) {
            return ['product.show', ['id' => $params['product_id']]];
        }
        if (isset($params['category_id'])) {
            return ['category.show', ['id' => $params['category_id']]];
        }
        if (isset($params['manufacturer_id'])) {
            return ['manufacturer.show', ['id' => $params['manufacturer_id']]];
        }
        if (isset($params['information_id'])) {
            return ['information.show', ['id' => $params['information_id']]];
        }

        return ['home', []];
    }

    /**
     * Clear SEO URL cache.
     */
    public function clearCache(): void
    {
        Cache::flush();
    }
}
