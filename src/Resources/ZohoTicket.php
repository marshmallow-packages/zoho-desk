<?php

namespace Marshmallow\ZohoDesk\Resources;

use Carbon\Carbon;
use Marshmallow\ZohoDesk\Facades\ZohoDesk;
use Marshmallow\ZohoDesk\Models\ZohoTicket as ZohoTicketModel;
use Marshmallow\ZohoDesk\Models\ZohoContact;
use Marshmallow\ZohoDesk\Models\ZohoProduct;

class ZohoTicket
{
    public function get($ticket_id)
    {
        return ZohoDesk::get("/tickets/{$ticket_id}");
    }

    public function setDueDate($ticket_id, Carbon $due_date)
    {
        return ZohoDesk::patch("/tickets/{$ticket_id}", [
            'dueDate' => ZohoDesk::dateFormat($due_date),
        ]);
    }

    public function comment($ticket_id, $comment, $is_public = false)
    {
        return ZohoDesk::post("/tickets/{$ticket_id}/comments", [
            'isPublic' => $is_public,
            'content' => $comment,
        ]);
    }

    public function list()
    {
        return ZohoDesk::get('/tickets');
    }

    public function create(ZohoContact $contact, Carbon $due_date, ZohoProduct $product = null, array $data): ZohoTicketModel
    {
        $ticket = ZohoDesk::post('/tickets', array_merge([
            'departmentId' => config('zohodesk.department_id'),
            'contactId' => $contact->id,
            'email' => $contact->email,
            'phone' => $contact->phone,
            'dueDate' => ZohoDesk::dateFormat($due_date),
            'channel' => config('zohodesk.default_channel'),
            'classification' => config('zohodesk.default_classification'),
            'language' => config('zohodesk.default_language'),
            'productId' => ($product) ? $product->id : null,
        ], $data));

        return ZohoTicketModel::make($ticket);
    }
}
