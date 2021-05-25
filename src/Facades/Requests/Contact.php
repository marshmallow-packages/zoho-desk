<?php

namespace Marshmallow\ZohoDesk\Facades\Requests;

use Illuminate\Support\Facades\Facade;
use Marshmallow\ZohoDesk\Requests\Contact as ContactRequest;

class Contact extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ContactRequest::class;
    }
}
