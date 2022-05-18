<?php

return [
    'active' => env('ZOHO_ACTIVE', false),

    'client_id' => env('ZOHO_CLIENT_ID'),

    'client_secret' => env('ZOHO_CLIENT_SECRET'),

    'department_id' => env('ZOHO_DEPARTMENT_ID'),

    'auth_host' => env('ZOHO_AUTH_HOST', 'https://accounts.zoho.eu/oauth/v2'),

    'desk_host' => env('ZOHO_DESK_HOST', 'https://desk.zoho.eu/api/v1'),

    'desk_portal_host' => env('ZOHO_DESK_PORTAL_HOST', 'https://desk.zoho.eu/portal/api'),

    'default_channel' => 'Web',

    'default_classification' => 'Request',

    'default_language' => 'Dutch',

    'scopes' => [
        'Desk.articles.READ',
        'Desk.articles.CREATE',
        'Desk.articles.UPDATE',
        'Desk.articles.DELETE',
        'Desk.products.CREATE',
        'Desk.products.READ',
        'Desk.products.DELETE',
        'Desk.products.UPDATE',
        'Desk.tickets.CREATE',
        'Desk.tickets.READ',
        'Desk.tickets.UPDATE',
        'Desk.tickets.DELETE',
        'Desk.contacts.CREATE',
        'Desk.contacts.READ',
        'Desk.contacts.UPDATE',
        'Desk.contacts.DELETE',
        'Desk.tasks.CREATE',
        'Desk.tasks.READ',
        'Desk.tasks.UPDATE',
        'Desk.tasks.DELETE',
        'Desk.calls.CREATE',
        'Desk.calls.READ',
        'Desk.calls.UPDATE',
        'Desk.calls.DELETE',
        'Desk.events.CREATE',
        'Desk.events.READ',
        'Desk.events.UPDATE',
        'Desk.events.DELETE',
        'Desk.settings.CREATE',
        'Desk.settings.READ',
        'Desk.settings.UPDATE',
        'Desk.settings.DELETE',
        'Desk.basic.CREATE',
        'Desk.basic.READ',
        'Desk.basic.UPDATE',
        'Desk.basic.DELETE',
        'Desk.activities.CREATE',
        'Desk.activities.READ',
        'Desk.activities.UPDATE',
        'Desk.activities.DELETE',
        'Desk.activities.tasks.CREATE',
        'Desk.activities.tasks.READ',
        'Desk.activities.tasks.UPDATE',
        'Desk.activities.tasks.DELETE',
        'Desk.activities.calls.CREATE',
        'Desk.activities.calls.READ',
        'Desk.activities.calls.UPDATE',
        'Desk.activities.calls.DELETE',
        'Desk.activities.events.CREATE',
        'Desk.activities.events.READ',
        'Desk.activities.events.UPDATE',
        'Desk.activities.events.DELETE',
        'Desk.search.READ',
    ],
];
