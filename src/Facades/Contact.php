<?php

namespace Marshmallow\ZohoDesk\Facades;

use Illuminate\Support\Facades\Facade;
use Marshmallow\ZohoDesk\Resources\ZohoContact;

class Contact extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ZohoContact::class;
    }
}
