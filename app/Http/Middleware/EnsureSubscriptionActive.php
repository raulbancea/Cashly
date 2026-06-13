<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;



class EnsureSubscriptionActive
{
    public function handle(Request $request, Closure $next)
    {
        
        $user = $request->user();

        
        if ($user === null || !$user->hasActiveSubscription()) {
            return redirect()->route('subscription.index');
        }

        
        return $next($request);
    }
}
