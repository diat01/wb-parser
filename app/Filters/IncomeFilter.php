<?php

namespace App\Filters;

use App\Models\Income;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;

class IncomeFilter implements FilterInterface
{
    public static function searchByRequest(FormRequest $request): Builder
    {
        return Income::query()
            ->whereBetween('date', [
                $request->dateFrom,
                $request->dateTo,
            ]);
    }
}
