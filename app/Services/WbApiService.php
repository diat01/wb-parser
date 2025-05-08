<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WbApiService
{
    protected string $baseUrl;
    protected mixed $apiKey;
    protected mixed $defaultLimit;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.wb_api.base_url'), '/').'/';
        $this->apiKey = config('services.wb_api.key');
        $this->defaultLimit = config('services.wb_api.default_limit', 500);
    }

    public function getAllData(string $dateFrom, string $dateTo, ?int $limit = null): array
    {
        return [
            'sales' => $this->getSales($dateFrom, $dateTo, 1, $limit),
            'orders' => $this->getOrders($dateFrom, $dateTo, 1, $limit),
            'stocks' => $this->getStocks($dateFrom, 1, $limit),
            'incomes' => $this->getIncomes($dateFrom, $dateTo, 1, $limit),
        ];
    }

    public function getSales(string $dateFrom, string $dateTo, int $page = 1, ?int $limit = null)
    {
        return $this->makeRequest('api/sales', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ], $page, $limit);
    }

    protected function makeRequest(string $endpoint, array $params = [], int $page = 1, ?int $limit = null)
    {
        try {
            $queryParams = array_merge($params, [
                'key' => $this->apiKey,
                'page' => $page,
                'limit' => $limit ?? $this->defaultLimit,
            ]);

            $response = Http::baseUrl($this->baseUrl)
                ->timeout(30)
                ->retry(3, 100)
                ->acceptJson()
                ->get($endpoint, $queryParams);

            if ($response->failed()) {
                Log::error("WB API Error [{$endpoint}]: ".$response->status().' - '.$response->body());
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error("WB API Exception [{$endpoint}]: ".$e->getMessage());
            return null;
        }
    }

    public function getOrders(string $dateFrom, string $dateTo, int $page = 1, ?int $limit = null)
    {
        return $this->makeRequest('api/orders', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ], $page, $limit);
    }

    public function getStocks(string $dateFrom, int $page = 1, ?int $limit = null)
    {
        return $this->makeRequest('api/stocks', [
            'dateFrom' => $dateFrom,
        ], $page, $limit);
    }

    public function getIncomes(string $dateFrom, string $dateTo, int $page = 1, ?int $limit = null)
    {
        return $this->makeRequest('api/incomes', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ], $page, $limit);
    }
}
