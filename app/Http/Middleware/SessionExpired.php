<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SessionExpired
{
   protected function redirectTo($request)
{
    if (! $request->expectsJson()) {
        return route('login', ['session_expired' => true]);
    }
}
}
