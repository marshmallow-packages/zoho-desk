<?php

namespace Marshmallow\ZohoDesk\Facades;

use Illuminate\Support\Facades\Facade;
use Marshmallow\ZohoDesk\Resources\ZohoProduct;

class Product extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ZohoProduct::class;
    }
}
