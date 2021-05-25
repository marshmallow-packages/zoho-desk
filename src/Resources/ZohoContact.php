<?php

namespace Marshmallow\ZohoDesk\Resources;

use Marshmallow\ZohoDesk\Facades\ZohoDesk;
use Marshmallow\ZohoDesk\Models\ZohoContact as ContactModel;

class ZohoContact
{
    public function get()
    {
    }

    public function findOrCreate(string $email, array $extra_data = []): ContactModel
    {
        $contact_exists = $this->findOneByEmail($email);
        if ($contact_exists) {
            return $contact_exists;
        }

        /*
         * Contact doesn't exist yet so lets create it.
         */
        return $this->create(array_merge($extra_data, [
            'email' => $email,
        ]));
    }

    public function findOneByEmail($email): ?ContactModel
    {
        $contact_exists = $this->search([
            'email' => $email,
        ]);
        if ($contact_exists->count()) {
            return ContactModel::make($contact_exists->first());
        }

        return null;
    }

    public function list()
    {
        return ZohoDesk::get('/contacts');
    }

    public function create(array $data)
    {
        $contact = ZohoDesk::post('/contacts', $data);

        return ContactModel::make($contact);
    }

    public function search(array $data)
    {
        return ZohoDesk::get('/contacts/search?' . http_build_query($data));
    }

    public function update(int $contact_id, array $data)
    {
        return ZohoDesk::patch("/v1/contacts/$contact_id", $data);
    }
}
