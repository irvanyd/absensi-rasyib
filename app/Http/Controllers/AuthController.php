<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function loginProcess(Request $request)
    {
        if (Auth::guard('pegawai')->attempt(['nuptk' => $request->nuptk, 'password' => $request->password])){
            return redirect('/dashboard');
        } else {
            return redirect('/')->with(['warning' => 'NUPTK / Password salah']);
        }
    }   

    public function logoutProcess()
    {
        if (Auth::guard('pegawai')->check()){
            Auth::guard('pegawai')->logout();
            return redirect('/');
        }
    }

    public function adminLoginProcess(Request $request)
    {
        if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password])){
            return redirect('/panel/dashboardAdmin');
        } else {
            return redirect('/panel')->with(['warning' => 'Email / Password salah']);
        }
    }          

    public function adminLogoutProcess()
    {
        if (Auth::guard('user')->check()){
            Auth::guard('user')->logout();
            return redirect('/panel');
        }
    }


}
