<?php

namespace App\Http\Filter;

use Illuminate\Database\Eloquent\Builder;

class Author
{
    public function handle(Builder $builder, \Closure $next)
    {
        if(request('author'))
        {
            $builder->where('author', 'LIKE', '%'.request('author').'%');
        }

        return $next($builder);
    }
}
