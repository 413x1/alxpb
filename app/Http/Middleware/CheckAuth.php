<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // cek sudah login atau belum . jika belum kembali ke halaman login
        if(!Auth::check()){
            return redirect('login.index');
        }
        //        //    simpan data user pada variabel $user
        //        $user = Auth::user();
        //
        //        //    jika user memiliki level sesuai pada kolom pada lanjutkan request
        //        if($user->role == $roles){
        //        }
        //
        //        //    jika tidak memiliki akses maka kembalikan ke halaman login
        //        return redirect('login')->with('error','Maaf anda tidak memiliki akses');
        return $next($request);
    }
}
