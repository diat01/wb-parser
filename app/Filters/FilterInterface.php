<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;

interface FilterInterface
{
    public static function searchByRequest(FormRequest $request): Builder;
}
