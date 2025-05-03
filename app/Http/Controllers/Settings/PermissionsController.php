<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PermissionsController extends Controller
{
    /**
     * Show the permission's index page.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('settings/Permissions', [
           
        ]);
    }

    /**
     * Create the permission's information.
     */
    public function store(ProfileUpdateRequest $request): RedirectResponse
    {
        // TODO
        return redirect('/');
    }

    /**
     * Update the permission's information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // TODO
        return redirect('/');
    }

    /**
     * Delete the permission's.
     */
    public function destroy(Request $request): RedirectResponse
    {
       // TODO
       return redirect('/');
    }
}
