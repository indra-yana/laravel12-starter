<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ACL\PermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PermissionController extends Controller
{

    public function __construct(
        public PermissionService $permissionService
    ) {}

    /**
     * Show the permission's index page.
     */
    public function index(Request $request): Response
    {
        $user = User::find($request->user_id);
        $permissions = $this->permissionService->getPermissionByGroupName();
        $selectedPermissions = $this->permissionService->getUserPermissions($user);

        return Inertia::render('settings/Permissions', [
            'permissions' => $permissions,
            'selected_permissions' => $selectedPermissions,
            'user' => $user,
        ]);
    }

    /**
     * Assign the permission's to the user.
     */
    public function assign(Request $request): RedirectResponse
    {
        try {
            $results = $this->permissionService->assignUserPermissions($request->user_id, $request->permissions);
            return sendSuccess($results, $results . ' permission(s) assigned.');
        } catch (\Throwable $th) {
            return sendError($th);
        }
    }
}
