<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date("Y-m-d");
        $bulanini = date("m") * 1;
        $tahunini = date("Y");
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", 
                    "Agustus", "September", "Oktober", "November", "Desember"];
        $nuptk = Auth::guard('pegawai')->user()->nuptk;
        $presensiToday = DB::table('presensi')->where('nuptk', $nuptk)->where('tgl_presensi', $hariini)->first();
        $historiBulanIni = DB::table('presensi')
            ->where('nuptk', $nuptk)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
            ->orderBy('tgl_presensi')
            ->get();
            
        $rekappresensi = DB::table('presensi')
        ->selectRaw('COUNT(nuptk) as jmlhadir, SUM(IF(jam_in > "07:00",1,0)) as jmlterlambat')
        ->where('nuptk', $nuptk)
        ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
        ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
        ->first();

        $leaderboard = DB::table('presensi')
        ->join('pegawai', 'presensi.nuptk', '=', 'pegawai.nuptk')
        ->where('tgl_presensi', $hariini)
        ->orderBy('jam_in')
        ->get();
        
        return view('dashboard.dashboard', compact('presensiToday', 'historiBulanIni', 
                    'namabulan', 'bulanini', 'tahunini', 'rekappresensi', 'leaderboard'));
    }
}
