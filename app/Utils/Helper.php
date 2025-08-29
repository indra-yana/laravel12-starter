<?php

use App\Utils\SendResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

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

if (!function_exists('config_pick'))
{
    function configPick(string $config, array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = config("{$config}.{$key}");
        }
        return $result;
    }
}
