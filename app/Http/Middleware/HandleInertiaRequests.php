<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware; // ⬅️ extend yang benar

class HandleInertiaRequests extends Middleware
{
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user'  => $request->user(),
                'roles' => $request->user()?->roleNames() ?? [],
            ],
        ]);
    }
}
