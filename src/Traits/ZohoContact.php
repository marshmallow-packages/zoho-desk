<?php

namespace Marshmallow\ZohoDesk\Traits;

use Marshmallow\ZohoDesk\Facades\Contact;

trait ZohoContact
{
    protected $zoho_synced_columns = [
        'first_name',
        'last_name',
        'phonenumber',
        'gender',
    ];

    public function updateZohoIfNecessary()
    {
        if ($this->shouldUpdateZoho()) {
            $this->updateZoho();
        }
    }

    public function createZoho()
    {
        return Contact::create(
            $this->getZohoContactDataArray()
        );
    }

    protected function updateZoho()
    {
        Contact::update($this->zoho_contact_id, $this->getZohoContactDataArray());
    }

    protected function shouldUpdateZoho()
    {
        if (!$this->zoho_contact_id) {
            return false;
        }

        foreach ($this->zoho_synced_columns as $column) {
            if ($this->isDirty($column)) {
                return true;
            }
        }

        return false;
    }

    public function getLastNameForZoho()
    {
        if ($this->last_name) {
            return $this->last_name;
        } elseif ($this->name) {
            return $this->name;
        } else {
            $email = explode('@', $this->email);

            return $email[0];
        }
    }

    protected function getZohoContactDataArray()
    {
        $data = [
            'email' => $this->email,
            'lastName' => $this->getLastNameForZoho(),
        ];

        if ($this->first_name) {
            $data['firstName'] = $this->first_name;
        }

        if ($this->phonenumber_formatted) {
            $data['phone'] = $this->phonenumber_formatted;
        }

        if ($this->city) {
            $data['city'] = $this->city;
        }

        return $data;
    }
}
