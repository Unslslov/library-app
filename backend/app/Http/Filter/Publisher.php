<?php

namespace App\Http\Filter;

use Illuminate\Database\Eloquent\Builder;

class Publisher
{
    public function handle(Builder $builder, \Closure $next)
    {
        if(request('publisher'))
        {
            $builder->where('publisher', 'LIKE', '%' . request('publisher') . '%');
        }

        return $next($builder);
    }
}
