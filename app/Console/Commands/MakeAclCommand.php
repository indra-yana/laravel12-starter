<?php

namespace App\Console\Commands;

use App\Services\ACL\PermissionService;
use App\Services\ACL\RoleService;
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
    public function handle(RoleService $roleService, PermissionService $permissionService)
    {
        $this->info("Generating role & permissions for the system...");
        $roles = $roleService->createRoles();
        $rolesCount = count($roles);

        $permissions = $permissionService->createPermissions();
        $permissionsCount = count($permissions);

        $this->info("$rolesCount Roles and  $permissionsCount Permissions generated successfully!");
    }
}
