<?php

namespace App\Filters;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;

class SaleFilter implements FilterInterface
{
    public static function searchByRequest(FormRequest $request): Builder
    {
        return Sale::query()
            ->whereBetween('date', [
                $request->dateFrom,
                $request->dateTo,
            ]);
    }
}
