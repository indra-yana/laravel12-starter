<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
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
        return Inertia::render('settings/Users', [
           
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
