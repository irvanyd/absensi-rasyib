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

    public function logoutProcess(Request $request)
    {
        if (Auth::guard('pegawai')->check()){
            Auth::guard('pegawai')->logout();
            return redirect('/');
        }
    }


}
