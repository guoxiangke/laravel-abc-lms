<?php

namespace App\Repositories;

use Torann\LaravelRepository\Repositories\AbstractRepository;

class RruleRepository extends AbstractRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    protected $model = \App\Models\Rrule::class;
}
