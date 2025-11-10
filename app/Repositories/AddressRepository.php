<?php

namespace App\Repositories;

use App\Eloquent\Repository;

class AddressRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'App\Models\Address';
    }
}
