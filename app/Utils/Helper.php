<?php

use Modules\Setting\Services\ACL\PermissionService;
use App\Utils\SendResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

function sendSuccess($result = [], $message = "Success!", $redirect = null, $code = 200): RedirectResponse|JsonResponse
{
    return SendResponse::success($result, $message, $redirect, $code, 'success');
}

function sendWarning($result = [], $message = "Warning!", $redirect = null, $code = 200): RedirectResponse|JsonResponse
{
    return SendResponse::warning($result, $message, $redirect, $code, 'warning');
}

function sendError(?Throwable $th = null, $redirect = null, $params = []): RedirectResponse|JsonResponse
{
    return SendResponse::error($th, $redirect, $params);
}

if (!function_exists('config_pick')) {
    function configPick(string $config, array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = config("{$config}.{$key}");
        }
        return $result;
    }
}

function hasRoutePermission(array|string $permission = null): bool
{
    $permisionService = new PermissionService();
    $permissions = $permisionService->getAllPermissions(true);
    $permission = $permission ?: Route::currentRouteName();

    if (in_array($permission, $permissions)) {
        return auth()->user()?->hasAnyPermission($permission);
    }

    return true;
}

function getMonthName(int $month, bool $short = false): string
{
    static $cache = [];
    $key = $month . ($short ? '_short' : '_long');

    if (isset($cache[$key])) {
        return $cache[$key];
    }

    $date = CarbonImmutable::create(2025, $month, 1)->locale(config('app.locale'));
    $cache[$key] = $short ? $date->translatedFormat('M') : $date->translatedFormat('F');

    return $cache[$key];
}

function mapMonthNameToShort(string $monthName): string
{
    static $mapCache = [];
    $locale = config('app.locale');

    if (isset($mapCache[$monthName])) {
        return $mapCache[$monthName];
    }

    if (empty($mapCache)) {
        foreach (range(1, 12) as $m) {
            $long = CarbonImmutable::create(2025, $m, 1)->locale($locale)->translatedFormat('F');
            $short = CarbonImmutable::create(2025, $m, 1)->locale($locale)->translatedFormat('M');
            $mapCache[$long] = $short;
        }
    }

    return $mapCache[$monthName] ?? $monthName;
}