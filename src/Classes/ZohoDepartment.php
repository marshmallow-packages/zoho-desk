<?php

namespace Marshmallow\ZohoDesk\Classes;

use Marshmallow\ZohoDesk\Facades\ZohoDesk;

class ZohoDepartment
{
    public static function get(){}

    public static function list()
    {
        return ZohoDesk::get('/departments');
    }

    public static function create(array $data){}
}
