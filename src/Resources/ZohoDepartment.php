<?php

namespace Marshmallow\ZohoDesk\Resources;

use Marshmallow\ZohoDesk\Facades\ZohoDesk;

class ZohoDepartment
{
    public function get()
    {
    }

    public function list()
    {
        return ZohoDesk::get('/departments');
    }

    public function create(array $data)
    {
    }
}
