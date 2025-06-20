<?php

namespace App\Http\Middleware;

use App\Services\ACL\PermissionService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');
        $user = $request->user();

        return [
            ...parent::share($request),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $user,
                'check' => Auth::check(),
                'permissions' => $user ? (new PermissionService)->getUserPermissions($user->id) : [],
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
                'query' => $request->query(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'flash' => fn() => [
                'type' => $request->session()->get('type'),
                'message' => $request->session()->get('message'),
                'response' => $request->session()->get('response'),
            ],
            'app' => fn() => [
                'name' => config('app.name'),
                'env' => config('app.env'),
                'version' => config('app.version'),
                'debug' => config('app.debug'),
                'url' => config('app.url'),
                'timezone' => config('app.timezone'),
                'locale' => function () {
                    if (session()->has('locale')) {
                        app()->setLocale(session('locale'));
                    }

                    return app()->getLocale();
                },
                'fallback_locale' => config('app.fallback_locale'),
            ],
        ];
    }
}
