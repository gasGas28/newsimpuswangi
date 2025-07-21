<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, $roles)
    {
        $roles = explode(',', $roles);

        Log::info('CHECK ROLE MIDDLEWARE', [
            'current_user_id' => optional($request->user())->id,
            'current_user_role' => optional($request->user())->role,
            'allowed_roles' => $roles,
            'path' => $request->path(),
            'method' => $request->method(),
        ]);

        if (! $request->user() || !in_array($request->user()->role, $roles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
