<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendorPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // if role is owner then no permission needed
        $vendor = auth('vendor')->user();
        if ($vendor->isOwner() || $vendor->role?->permissions->contains('name', $role)) {
            return $next($request);
        }

        if ($request->ajax()) {
            return response()->json(['message' => "Permission denied"],403);
        }
        $previousUrl = url()->previous();
        $encodedUrl = urlencode($previousUrl);
        return redirect()->route('vendor.forbidden', ['prevUrl' => $encodedUrl]);
    }
}
