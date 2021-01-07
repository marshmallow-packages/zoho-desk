<?php

namespace Marshmallow\ZohoDesk\Classes;

use Marshmallow\ZohoDesk\Facades\ZohoDesk;

class ZohoContact
{
    public static function get()
    {
    }

    public static function list()
    {
        return ZohoDesk::get('/contacts');
    }

    public static function create(array $data)
    {
        return ZohoDesk::post('/contacts', $data);
    }

    public static function search(array $data)
    {
        return ZohoDesk::get('/contacts/search?'.http_build_query($data));
    }

    public static function update(int $contact_id, array $data)
    {
        return ZohoDesk::patch("/v1/contacts/$contact_id", $data);
    }

    public static function profiles(){}
    public static function listByIds(){}
    public static function tickets(){}
    public static function products(){}
    public static function count(){}
    public static function statistics(){}
    public static function merge(){}
    public static function markAsSpam(){}
    public static function associateProducts(){}
    public static function history(){}
    public static function inviteAsEndUser(){}
    public static function inviteMultipleAsEndUser(){}
    public static function helpCenters(){}
}
