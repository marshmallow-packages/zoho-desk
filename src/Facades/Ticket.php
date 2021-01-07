<?php

namespace Marshmallow\ZohoDesk\Facades;

use Illuminate\Support\Facades\Facade;
use Marshmallow\ZohoDesk\Resources\ZohoTicket;

class Ticket extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ZohoTicket::class;
    }
}
