<?php

namespace App\Http\Middleware;

use App\Constants\Constants;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsRoleSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = $request->route('role');

        if($role->name === Constants::ROLE_SUPER_ADMIN) {
            return redirect()->back()->with('error', 'The status could not be changed');
        }
        
        return $next($request);
    }
}
