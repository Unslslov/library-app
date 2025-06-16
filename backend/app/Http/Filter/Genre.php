<?php

namespace App\Http\Filter;

use Illuminate\Database\Eloquent\Builder;

class Genre
{
    public function handle(Builder $builder, \Closure $next)
    {
        if(request('genre'))
        {
            $builder->where('genre', 'LIKE', '%' . request('genre') . '%');
        }

        return $next($builder);
    }
}
