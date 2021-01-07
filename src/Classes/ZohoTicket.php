<?php

namespace Marshmallow\ZohoDesk\Classes;

use Marshmallow\ZohoDesk\Facades\ZohoDesk;

class ZohoTicket
{
    public static function get()
    {
    }

    public static function list()
    {
        return ZohoDesk::get('/tickets');
    }

    public static function create(array $data)
    {
        return ZohoDesk::post('/tickets', $data);
    }
}
