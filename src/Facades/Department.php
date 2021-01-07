<?php

namespace Marshmallow\ZohoDesk\Facades;

use Illuminate\Support\Facades\Facade;
use Marshmallow\ZohoDesk\Resources\ZohoDepartment;

class Department extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ZohoDepartment::class;
    }
}
