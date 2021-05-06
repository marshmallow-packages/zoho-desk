<?php

namespace Marshmallow\ZohoDesk\Requests;

use Carbon\Carbon;
use Marshmallow\ZohoDesk\Facades\Ticket as TicketFacade;

class Ticket
{
    protected $ticket_id;
    protected $due_date;
    protected $comment = [];
    protected $attachment = [];

    public function of($ticket_id)
    {
        $this->ticket_id = $ticket_id;
        return $this;
    }

    public function setDueDate(Carbon $due_date)
    {
        $this->due_date = $due_date;
        return $this;
    }

    public function comment(string $comment, bool $public = false)
    {
        $this->comment[] = [
            'comment' => $comment,
            'public' => $public,
        ];

        return $this;
    }

    public function attachment(string $relative_path, string $field_name = 'file')
    {
        $this->attachment[] = [
            'relative_path' => $relative_path,
            'field_name' => $field_name,
        ];

        return $this;
    }

    public function post()
    {
        if ($this->due_date) {
            TicketFacade::setDueDate($this->ticket_id, $this->due_date);
        }

        if ($this->comment && !empty($this->comment)) {
            foreach ($this->comment as $comment) {
                TicketFacade::comment($this->ticket_id, $comment['comment'], $comment['public']);
            }
        }

        if ($this->attachment && !empty($this->attachment)) {
            foreach ($this->attachment as $attachment) {
                TicketFacade::attachment($this->ticket_id, $attachment['relative_path'], $attachment['field_name']);
            }
        }
    }
}
