<?php

namespace Marshmallow\ZohoDesk\Facades\Requests;

use Illuminate\Support\Facades\Facade;
use Marshmallow\ZohoDesk\Requests\Ticket as TicketRequest;

class Ticket extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TicketRequest::class;
    }
}
