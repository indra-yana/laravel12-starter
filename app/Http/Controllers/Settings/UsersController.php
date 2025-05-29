<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
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
        $this->userService->store($request->all());

        return back()->with('success', 'User created!');
    }

    /**
     * Update the user's information.
     */
    public function update(Request $request): RedirectResponse
    {
        $this->userService->update($request->id, $request->all());

        return back()->with('success', 'User updated!');
    }

    /**
     * Delete the user's.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $ids = $request->input('ids');
        if (!$ids) {
            return redirect()->back()->with('error', 'No user ID(s) provided.');
        }

        $this->userService->destroy($request->all());
        return back()->with('success', count($ids) . ' user(s) deleted successfully.');
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
