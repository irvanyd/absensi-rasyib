<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = DB::table('pegawai')->orderBy('nama_lengkap')
        ->join('departemen', 'pegawai.kode_dept', '=','departemen.kode_dept')
        ->get();
        return view('pegawai.index', compact('pegawai'));
    }
}
