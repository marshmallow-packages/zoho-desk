<?php

namespace Marshmallow\ZohoDesk\Facades;

class ZohoDesk extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return \Marshmallow\ZohoDesk\ZohoDesk::class;
    }
}
