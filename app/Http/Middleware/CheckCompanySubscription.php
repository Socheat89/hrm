<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckCompanySubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for unauthenticated users
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Skip for super admin
        if ($user->is_super_admin) {
            return $next($request);
        }

        // Must belong to a company
        $company = $user->company;

        if (!$company) {
            // Probably should show an error or logout
            Auth::logout();
            return redirect()->route('login')->withErrors(['error' => 'Your account is not associated with any company.']);
        }

        // Check if company is active
        if ($company->status !== 'active') {
             // In real app, we might redirect to a special suspended page
             Auth::logout();
             return redirect()->route('login')->withErrors(['error' => 'Your company account is currently deactivated or suspended.']);
        }

        // Check if subscription has expired
        if ($company->expiry_date && now()->gt($company->expiry_date)) {
            // Let them access billing routes potentially (if they have permissions)
            if ($request->routeIs('billing.*') || $request->routeIs('logout')) {
                return $next($request);
            }
            
            // Or redirect to a friendly "subscription expired" page
            // Assuming this route doesn't exist yet, we can abort with a 402 Payment Required
            abort(402, 'Your company subscription has expired. Please contact your company administrator to renew.');
        }

        return $next($request);
    }
}
