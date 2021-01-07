<?php

namespace Marshmallow\ZohoDesk\Commands;

use Exception;
use Illuminate\Console\Command;
use Marshmallow\ZohoDesk\Facades\ZohoDesk;

class ZohoDeskAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho-desk:auth';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client_id = $this->ask('Please enter your client id?', config('zohodesk.client_id'));
        $client_secret = $this->ask('Please enter your client secret?', config('zohodesk.client_secret'));

        $this->info("Step 1: Go to https://api-console.zoho.eu/client/$client_id");
        $this->line('Step 2: Enter the scope information as listed below in the scopes field');
        $this->line('Step 3: Set the time duration to 3 minutes');
        $this->line('Step 4: Add a random description to the discription field');
        $this->line('Step 5: Press create');
        $this->line('Step 5: Copy the generated code in this terminal');

        $this->newLine();
        $this->line('Your scopes to add to the Zoho api console:');
        $this->line(join(',', config('zohodesk.scopes')));
        $this->newLine();
        $code = $this->ask('Please enter the temporary code?');

        try {
            ZohoDesk::auth([
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'code' => $code,
            ]);
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return 0;
        }

        $this->info('Auth was successfull!');

        return 0;
    }
}
