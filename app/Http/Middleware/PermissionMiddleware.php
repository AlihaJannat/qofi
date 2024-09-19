<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // if role is admin then no permission needed
        if (auth('admin')->id() == 1 || auth('admin')->user()->roleRel?->permissions->contains('name', $role)) {
            return $next($request);
        }

        if ($request->ajax()) {
            return response()->json(['message' => "Permission denied"],403);
        }
        $previousUrl = url()->previous();
        $encodedUrl = urlencode($previousUrl);
        return redirect()->route('admin.forbidden', ['prevUrl' => $encodedUrl]);
    }
}
