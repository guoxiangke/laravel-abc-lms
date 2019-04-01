<?php

namespace App\Repositories;

use Torann\LaravelRepository\Repositories\AbstractRepository;

class OrderRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = \App\Models\Order::class;


}
