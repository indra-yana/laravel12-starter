<?php

namespace App\Http\Controllers\Settings;

use App\Enums\UserTableEnum;
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
        $allowedFilters = UserTableEnum::allowedFilters();
        $columnFilters = $request->input('column_filters');
        $search = $request->input('search');
        $sorting = in_array($request->input('sorting'), UserTableEnum::values())
            ? $request->input('sorting')
            : UserTableEnum::Name->value;
        $direction = in_array($request->input('direction'), ['asc', 'desc'])
            ? $request->input('direction')
            : 'asc';

        $users = User::query()
            ->when(
                $search && !$columnFilters,
                function ($query) use ($allowedFilters, $search) {
                    foreach ($allowedFilters as $columnFilter) {
                        $query->orWhere($columnFilter, 'ilike', "%{$search}%");
                    }
                }
            )
            ->when($columnFilters && $search && is_array($columnFilters), function ($query) use ($columnFilters, $allowedFilters) {
                foreach ($columnFilters as $filter) {
                    $column = $filter['id'] ?? null;
                    $value = $filter['value'] ?? null;

                    if ($column && $value !== null && in_array($column, $allowedFilters)) {
                        $query->orWhere($column, 'ilike', "%{$value}%");
                    }
                }
            })
            ->orderBy($sorting, $direction)
            ->paginate($request->input('per_page', 10))
            ->withQueryString();

        return response()->json($users);
    }
}
