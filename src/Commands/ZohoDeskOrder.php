<?php

namespace Marshmallow\ZohoDesk\Commands;

use Illuminate\Console\Command;

class ZohoDeskOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho-desk:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create and manage orders. This is for sandbox/testing use only.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return 0;
    }
}
