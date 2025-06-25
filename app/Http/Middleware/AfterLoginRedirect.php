<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AfterLoginRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $usertype = Auth::user()->usertype; // Mengambil usertype dari user yang login

            // Redirect berdasarkan usertype
            switch ($usertype) {
                case 'marketing':
                    return redirect()->route('marketing.dashboard');
                case 'supervisor':
                    return redirect()->route('supervisor.dashboard');
                case 'credit':
                    return redirect()->route('creditAnalyst.dashboard');
                case 'head':
                    return redirect()->route('headMarketing.dashboard');
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'root':
                    return redirect()->route('superAdmin.dashboard');
                case 'surveyor':
                    return redirect()->route('surveyor.dashboard');
                default:
                    return redirect('/'); // Redirect default jika usertype tidak dikenal
            }
        }

        return $next($request);
    }
}
