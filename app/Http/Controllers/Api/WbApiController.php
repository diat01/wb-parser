<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WbApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WbApiController extends Controller
{
    protected WbApiService $wbService;

    public function __construct(WbApiService $wbService)
    {
        $this->wbService = $wbService;
    }

    public function sales(Request $request): JsonResponse
    {
        $request->validate([
            'dateFrom' => 'required|date_format:Y-m-d',
            'dateTo' => 'required|date_format:Y-m-d',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:500',
        ]);

        $data = $this->wbService->getSales(
            $request->dateFrom,
            $request->dateTo,
            $request->page ?? 1,
            $request->limit
        );

        return response()->json($data ?? ['error' => 'Failed to fetch sales data']);
    }

    public function orders(Request $request): JsonResponse
    {
        $request->validate([
            'dateFrom' => 'required|date_format:Y-m-d',
            'dateTo' => 'required|date_format:Y-m-d',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:500',
        ]);

        $data = $this->wbService->getOrders(
            $request->dateFrom,
            $request->dateTo,
            $request->page ?? 1,
            $request->limit
        );

        return response()->json($data ?? ['error' => 'Failed to fetch orders data']);
    }

    public function stocks(Request $request): JsonResponse
    {
        $request->validate([
            'dateFrom' => 'required|date_format:Y-m-d',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:500',
        ]);

        $data = $this->wbService->getStocks(
            $request->dateFrom,
            $request->page ?? 1,
            $request->limit
        );

        return response()->json($data ?? ['error' => 'Failed to fetch stocks data']);
    }

    public function incomes(Request $request): JsonResponse
    {
        $request->validate([
            'dateFrom' => 'required|date_format:Y-m-d',
            'dateTo' => 'required|date_format:Y-m-d',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:500',
        ]);

        $data = $this->wbService->getIncomes(
            $request->dateFrom,
            $request->dateTo,
            $request->page ?? 1,
            $request->limit
        );

        return response()->json($data ?? ['error' => 'Failed to fetch incomes data']);
    }

    public function allData(Request $request): JsonResponse
    {
        $request->validate([
            'dateFrom' => 'required|date_format:Y-m-d',
            'dateTo' => 'required|date_format:Y-m-d',
            'limit' => 'sometimes|integer|min:1|max:500',
        ]);

        $data = [
            'sales' => $this->wbService->getSales(
                $request->dateFrom,
                $request->dateTo,
                1,
                $request->limit
            ),
            'orders' => $this->wbService->getOrders(
                $request->dateFrom,
                $request->dateTo,
                1,
                $request->limit
            ),
            'stocks' => $this->wbService->getStocks(
                $request->dateFrom,
                1,
                $request->limit
            ),
            'incomes' => $this->wbService->getIncomes(
                $request->dateFrom,
                $request->dateTo,
                1,
                $request->limit
            ),
        ];

        return response()->json($data);
    }
}
