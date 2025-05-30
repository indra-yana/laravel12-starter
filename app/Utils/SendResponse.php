<?php

namespace App\Utils;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class SendResponse
{

    private static $errorName = 'default';

    public static function success($result = [], $message = "Success!", $redirect = null, $code = 200, $type = 'success'): RedirectResponse | JsonResponse
    {
        $response = [
            'code' => $code,
            'message' => $message,
            'data' => $result,
        ];

        $isAjax = request()->wantsJson();

        if (!$redirect && !$isAjax) {
            return self::back(false, [
                'result' => $result,
                'type' => $type,
                'message' => $message
            ]);
        }

        if ($isAjax) {
            return response()->json($response, $code);
        }

        return redirect($redirect)
            ->with("type", $type)
            ->with("message", $message)
            ->with("response", $response)
            ->with($response);
    }

    public static function warning($result = [], $message = "Warning!", $redirect = null, $code = 200): RedirectResponse | JsonResponse
    {
        return self::success($result, $message, $redirect, $code, 'warning');
    }

    public static function error(?Throwable $th = null, $redirect = null, $params = []): RedirectResponse | JsonResponse
    {
        $code = $th ? ($th->getCode() > 505 || $th->getCode() == 0 ? 500 : $th->getCode()) : 500;
        $message = "Woops! an error occured. " . $th?->getMessage();
        $originMessage = $message;

        if (Str::contains($message, ['SQLSTATE', 'SQL:']) && !config('app.debug')) {
            $message = "Error when execute query, please check log to see detail.";
        }

        $errors = [];
        if ($th instanceof ValidationException) {
            $errors = $th->errors();
            $code = $th->status;
        } else {
            if (config('app.debug') && $th) {
                throw $th;
            }
        }

        Log::error($message, [
            'code' => $code,
            'err_msg' => $originMessage,
            'params' => request()->all(),
            'auth' => Auth::user(),
            'errors' => $errors,
            'trace' => $th?->getTraceAsString(),
        ]);

        $isAjax = request()->wantsJson();
        if (!$redirect && !$isAjax) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($errors, self::$errorName)
                ->with('params', $params)
                ->with("type", "error")
                ->with("message", $message);
        }

        if ($isAjax) {
            return response()->json([
                'code' => $code,
                'message' => $message,
                'errors' => $errors,
            ], $code);
        }

        return redirect($redirect)
            ->withInput()
            ->withErrors($errors, self::$errorName)
            ->with('params', $params)
            ->with("type", "error")
            ->with("message", $message);
    }

    public static function setErrorName($errorName)
    {
        self::$errorName = $errorName;

        return new static;
    }

    public static function back($withExtra = false, $params = []): RedirectResponse
    {
        if ($withExtra) {
            return back()
                ->withInput()
                ->withErrors($params["errors"] ?? [], self::$errorName)
                ->with("type", $params["type"] ?? "error")
                ->with("message", $params["message"] ?? "")
                ->with("response", $params);
        }

        return back()
            ->with('type', $params["type"] ?? "error")
            ->with('message', $params["message"] ?? "-")
            ->with('data', $params["data"] ?? null)
            ->with("response", $params);
    }
}
