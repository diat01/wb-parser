<?php

namespace App\Filters;

use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;

class StockFilter implements FilterInterface
{
    public static function searchByRequest(FormRequest $request): Builder
    {
        return Stock::query()->whereBetween('created_at', [
            $request->dateFrom,
            Carbon::tomorrow()->format('Y-m-d H:i:s'),
        ]);
    }
}
