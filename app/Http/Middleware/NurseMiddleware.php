<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NurseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('nurse')) {
            Session::flash('warning', 'Silakan login terlebih dahulu!');
            return redirect()->route('login');
        }
        return $next($request);
    }
}