<?php

namespace App\Filters;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;

class OrderFilter implements FilterInterface
{
    public static function searchByRequest(FormRequest $request): Builder
    {
        return Order::query()
            ->whereBetween('date', [
                $request->dateFrom,
                $request->dateTo,
            ]);
    }
}
