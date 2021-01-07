<?php

namespace Marshmallow\ZohoDesk\Traits;

use Marshmallow\ZohoDesk\Facades\Contact;
use Marshmallow\ZohoDesk\Facades\ZohoDesk;
use Marshmallow\ZohoDesk\Resources\ZohoTicket;

trait ZohoLead
{
    public function addToZoho()
    {
        if (!$this->zoho_contact_id) {
            $contact_exists = Contact::search([
                'email' => $this->email,
            ]);

            if ($contact_exists->count()) {
                $this->connectZohoContactToLead(
                    $contact_exists->first()
                );
            } else {
                $contact = Contact::create([
                    'lastName' => $this->last_name,
                    'firstName' => $this->first_name,
                    'email' => $this->email,
                    'phone' => $this->phonenumber,
                    'city' => $this->city,
                ]);

                $this->connectZohoContactToLead(
                    $contact
                );
            }
        }

        if (!$this->zoho_ticket_id) {
            $ticket = ZohoTicket::create([
                'subject' => 'Website lead #'.$this->id,
                'departmentId' => config('zoho.department_id'),
                'contactId' => $this->zoho_contact_id,
                'email' => $this->email,
                'phone' => $this->phonenumber,
                'dueDate' => ZohoDesk::dateFormat($this->created_at->addMinutes(10)),
                'description' => $this->comment,
                'cf' => [
                    'cf_link_to_cms' => config('app.url').'/marshmallow/resources/leads/'.$this->id,
                ],
                'channel' => 'Web',
                'classification' => 'Request', // Problem, Request, Question, and Others.
                'language' => 'Dutch',
            ]);

            $this->connectZohoTicketToLead(
                $ticket
            );
        }
    }

    protected function connectZohoTicketToLead(array $ticket)
    {
        $this->update([
            'zoho_ticket_id' => $ticket['id'],
        ]);
    }

    protected function connectZohoContactToLead(array $contact)
    {
        $this->update([
            'zoho_contact_id' => $contact['id'],
        ]);
    }
}
