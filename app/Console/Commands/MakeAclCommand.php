<?php

namespace App\Console\Commands;

use App\Services\ACL\PermissionService;
use Illuminate\Console\Command;

class MakeAclCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:acl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create Access Control List.';

    /**
     * Execute the console command.
     */
    public function handle(PermissionService $permissionService)
    {
        $this->info("Creating permissions...");

        $results = $permissionService->createPermissions();
        $resultCount = count($results);

        $this->info("$resultCount Permissions created successfully!");
    }
}
