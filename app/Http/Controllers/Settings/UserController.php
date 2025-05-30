<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{

    public function __construct(
        public UserService $userService
    ) {}

    /**
     * Show the user's index page.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('settings/Users', []);
    }

    /**
     * Create the user's information.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'is_active' => ['required', 'boolean'],
            ]);

            $results = $this->userService->store($request->all());

            return sendSuccess($results);
        } catch (\Throwable $th) {
            return sendError($th);
        }
    }

    /**
     * Update the user's information.
     */
    public function update(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'id' => ['required', 'exists:users,id'],
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($request->id),
                ],
                'is_active' => ['required', 'boolean'],
            ]);

            $results = $this->userService->update($request->id, $request->all());

            return sendSuccess($results);
        } catch (\Throwable $th) {
            return sendError($th);
        }
    }

    /**
     * Delete the user's.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'ids' => ['required', 'array'],
                'ids.*' => ['integer', 'exists:users,id'],
            ]);

            $ids = $request->input('ids');
            if (!$ids) {
                return sendWarning($ids,  'No user ID(s) provided.');
            }

            $results = $this->userService->destroy($request->ids);

            return sendSuccess($results,  count($ids) . ' user(s) deleted successfully.');
        } catch (\Throwable $th) {
            return sendError($th);
        }
    }

    /**
     * Get user's list.
     */
    public function dataTable(Request $request)
    {
        $users = $this->userService->getAll($request->all());
        return response()->json($users);
    }
}
