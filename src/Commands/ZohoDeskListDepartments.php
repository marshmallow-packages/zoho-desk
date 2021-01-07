<?php

namespace Marshmallow\ZohoDesk\Commands;

use Illuminate\Console\Command;
use Marshmallow\ZohoDesk\Facades\Department;

class ZohoDeskListDepartments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho-desk:list-departments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List the available departments in your Zoho Desk';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $departments = Department::list()->map(function ($item) {
            return [
                $item['id'],
                $item['name'],
            ];
        });

        $this->table(
            ['ID', 'Name'],
            $departments->toArray()
        );

        return 0;
    }
}
