<?php

namespace App\Http\Queries;

use App\Models\TripOrder;
use Spatie\QueryBuilder\QueryBuilder;

class TripOrderQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(TripOrder::query());

        $this->allowedSorts([
            'created_at',
            'departure_at',
            'destination',
            'id',
            'requester_name',
            'return_at',
            'status',
        ]);

        $this->allowedFilters([
            'created_at',
            'destination',
            'id',
            'requester_name',
            'status',
        ]);

        $this->defaultSort('-id');
    }
}
