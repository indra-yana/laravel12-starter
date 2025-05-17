<?php

namespace App\Http\Controllers\Settings;

use App\Enums\UserSortEnum;
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
        return Inertia::render('settings/Users', []);
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

    function dataTable(Request $request)
    {
        $search = $request->input('search');
        $sorting = in_array($request->input('sorting'), UserSortEnum::values())
            ? $request->input('sorting')
            : UserSortEnum::Name->value;
        $direction = in_array($request->input('direction'), ['asc', 'desc'])
            ? $request->input('direction')
            : 'asc';

        $users = User::query()
            ->when(
                $search,
                fn($q) =>
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
            )
            ->when($request->name, fn($q) => $q->where('name', 'like', "%{$request->name}%"))
            ->when($request->email, fn($q) => $q->where('email', 'like', "%{$request->email}%"))
            ->orderBy($sorting, $direction)
            ->paginate($request->input('per_page', 10))
            ->withQueryString();

        return response()->json($users);
    }
}
