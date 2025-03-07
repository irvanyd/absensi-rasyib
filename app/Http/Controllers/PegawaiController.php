<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;


class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::query();
        $query->select('pegawai.*', 'nama_dept');
        $query->join('departemen', 'pegawai.kode_dept', '=', 'departemen.kode_dept');
        $query->orderBy('nama_lengkap');
        $pegawai = $query->paginate(5);
        if (!empty($request->nama_pegawai)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_pegawai . '%');
        }

        if (!empty($request->kode_dept)) {
            $query->where('pegawai.kode_dept', 'like', '%' . $request->kode_dept);
        }
        $pegawai = $query->paginate(5);


        $departemen = DB::table('departemen')->get();
        return view('pegawai.index', compact('pegawai', 'departemen'));
    }

    public function store(Request $request)
    {
        $nuptk = $request->nuptk;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        // $password = Hash::make('12345'); //password default
        $password = Hash::make($request->password);
        if ($request->hasFile('foto')) {
            $foto = $nuptk . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = null;
        }

        try {
            $data = [
                'nuptk' => $nuptk,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password
            ];
            $simpan = DB::table('pegawai')->insert($data);
            if ($simpan) {
                if ($request->hasFile('foto')) {
                    $folderPath = "public/uploads/pegawai/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data berhasil disimpan']);
            } 
        } catch (\Exception $e) {
            //dd($e->message);
            return Redirect::back()->with(['error' => 'Data gagal disimpan']);

        }
    }

    public function edit(Request $request)
    {
        $nuptk = $request->nuptk;
        $departemen = DB::table('departemen')->get();
        $pegawai = DB::table('pegawai')->where('nuptk', $nuptk)->first();
        return view('pegawai.edit', compact('departemen', 'pegawai'));
    }

    public function update($nuptk, Request $request)
    {
        $nuptk = $request->nuptk;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        $password = Hash::make($request->password);
        // $password = Hash::make('12345'); //password default
        $old_foto = $request->old_foto;
        if ($request->hasFile('foto')) {
            $foto = $nuptk . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }

        try {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password
            ];
            $update = DB::table('pegawai')->where('nuptk', $nuptk)->update($data);
            if ($update) {
                if ($request->hasFile('foto')) {
                    $folderPath = "public/uploads/pegawai/";
                    $folderPathOld = "public/uploads/pegawai/". $old_foto;
                    Storage::delete($folderPathOld);
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data berhasil diupdate']);
            } 
        } catch (\Exception $e) {
            //dd($e->message);
            return Redirect::back()->with(['error' => 'Data gagal diupdate']);

        }
    }

    public function  delete($nuptk)
    {
        $delete = DB::table('pegawai')->where('nuptk', $nuptk)->delete();
        if ($delete) {
            return Redirect::back()->with(['success'=>'Data berhasil dihapus']);
        } else {
            return Redirect::back()->with(['warning'=>'Data gagal dihapus']);
        }
    }
}
