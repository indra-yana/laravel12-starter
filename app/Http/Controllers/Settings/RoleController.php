<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ACL\PermissionService;
use App\Services\ACL\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{

    public function __construct(
        public RoleService $roleService,
        public PermissionService $permissionService
    ) {
    }

    /**
     * Show the roles's index page.
     */
    public function index(Request $request): Response
    {
        $user = User::find($request->user_id);
        $roles = $this->roleService->getAllRoles();
        $selectedRoles = $this->roleService->getUserRole($user);
        $selectedPermissions = $this->permissionService->getUserPermissions($user);

        return Inertia::render('settings/Roles', [
            'roles' => $roles,
            'selected_role' => $selectedRoles,
            'selected_permissions' => $selectedPermissions,
            'user' => $user,
        ]);
    }

    /**
     * Assign the roles's to the user.
     */
    public function assign(Request $request): RedirectResponse
    {
        try {
            $results = $this->roleService->assignUserRoleAndPermissions($request->user_id, $request->role, $request->permissions);
            return sendSuccess($results, "$results Roles(s) assigned.");
        } catch (\Throwable $th) {
            return sendError($th);
        }
    }
}
