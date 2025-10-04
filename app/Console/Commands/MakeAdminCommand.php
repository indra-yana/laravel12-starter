<?php

namespace App\Console\Commands;

use Modules\Setting\Services\ACL\RoleService;
use Illuminate\Console\Command;

class MakeAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create default Admin.';

    /**
     * Execute the console command.
     */
    public function handle(RoleService $roleService)
    {
        $this->info("Generating default admin for the system...");
        $admin = $roleService->createAdmin();
        $adminCount = count($admin);

        $this->info("$adminCount Admin created successfully!, please login using this default account");
        $this->newLine();

        // langsung bentuk rows tanpa split string
        $rows = collect($admin)->map(function ($account) {
            return [
                'Role' => $account['role'],
                'Email' => $account['email'],
                'Password' => $account['password'],
            ];
        })->values()->toArray();

        $this->table(
            ['Role', 'Email', 'Password'],
            $rows
        );

        $this->newLine();
        $this->warn("Please change the default password immediately after login!");
    }
}
