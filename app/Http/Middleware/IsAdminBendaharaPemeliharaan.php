<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminBendaharaPemeliharaan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //DITERIMA JIKA ROLE ADMIN, BENDAHARA DAN PEMELIHARAAN & PEMBANGUNAN
        if($request->session()->get('role') == 1 || $request->session()->get('role') == 2 || $request->session()->get('role') == 3){  
            return $next($request);
        }
        return redirect('dashboard')->with('message', 'Hak Akses Tidak Terpenuhi');
    }
}
