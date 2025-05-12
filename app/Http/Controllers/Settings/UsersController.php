<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    /**
     * Show the user's index page.
     */
    public function index(Request $request): Response
    {
        $users = User::query()
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->when($request->name, fn ($q) => $q->where('name', 'like', "%{$request->name}%"))
            ->when($request->email, fn ($q) => $q->where('email', 'like', "%{$request->email}%"))
            ->orderBy('id', 'desc')
            ->paginate($request->input('per_page', 10))
            ->withQueryString();

        return Inertia::render('settings/Users', [
            'users' => $users,
            'filters' => $request->only(['search', 'name', 'email', 'per_page']),
        ]);
    }

    /**
     * Create the user's information.
     */
    public function store(ProfileUpdateRequest $request): RedirectResponse
    {
        // TODO
        return redirect('/');
    }

    /**
     * Update the user's information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // TODO
        return redirect('/');
    }

    /**
     * Delete the user's.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // TODO
        return redirect('/');
    }
}
