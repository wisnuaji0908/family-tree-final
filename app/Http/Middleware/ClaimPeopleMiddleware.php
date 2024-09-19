<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClaimPeopleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah pengguna sudah login dan memiliki people_id
        if (auth()->check() && auth()->user()->role =='people' && is_null(User::where('id', request()->user()->id)->whereHas('people')->first())) {
            // Jika belum ada people_id, arahkan ke halaman klaim
            return redirect('/people/claim')->with('message', 'Please claim your account first.');
        }

        // Jika sudah ada people_id, lanjutkan request
        return $next($request);
    }


    }
