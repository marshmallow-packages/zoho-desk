<?php

namespace Marshmallow\ZohoDesk\Requests;

use Marshmallow\ZohoDesk\Facades\ZohoDesk;

class Contact
{
    protected $ticket_id;

    public function of($contact_id)
    {
        $this->contact_id = $contact_id;
        return $this;
    }

    public function tickets(array $include = [])
    {
        $include = (!empty($include)) ? '?include=' . join(',', $include) : '';
        return ZohoDesk::get("/contacts/{$this->contact_id}/tickets{$include}");
    }
}
