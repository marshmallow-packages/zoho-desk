<?php

namespace Marshmallow\ZohoDesk\Resources;

use Carbon\Carbon;
use Marshmallow\ZohoDesk\Facades\ZohoDesk;
use Marshmallow\ZohoDesk\Models\ZohoContact;
use Marshmallow\ZohoDesk\Models\ZohoProduct;

class ZohoTicket
{
    public function get()
    {
    }

    public function list()
    {
        return ZohoDesk::get('/tickets');
    }

    public function create(ZohoContact $contact, Carbon $due_date, ZohoProduct $product = null, array $data)
    {
        $ticket = ZohoDesk::post('/tickets', array_merge([
            'departmentId' => config('zohodesk.department_id'),
            'contactId' => $contact->id,
            'email' => $contact->email,
            'phone' => $contact->phonenumber,
            'dueDate' => ZohoDesk::dateFormat($due_date),
            'channel' => config('zohodesk.default_channel'),
            'classification' => config('zohodesk.default_classification'),
            'language' => config('zohodesk.default_language'),
            'productId' => ($product) ? $product->id : null,
        ], $data));

        dd($ticket);
    }
}
