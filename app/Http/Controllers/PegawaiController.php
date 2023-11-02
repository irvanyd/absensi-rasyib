<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::query();
        $query->select('pegawai.*', 'nama_dept');
        $query->join('departemen', 'pegawai.kode_dept', '=', 'departemen.kode_dept');
        $query->orderBy('nama_lengkap');
        $pegawai = $query->paginate(3);
        if (!empty($request->nama_lengkap)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        if (!empty($request->kode_dept)) {
            $query->where('pegawai.kode_dept', 'like', '%' . $request->kode_dept);
        }
        $pegawai = $query->paginate(3);


        $departemen = DB::table('departemen')->get();
        return view('pegawai.index', compact('pegawai', 'departemen'));
    }
}
