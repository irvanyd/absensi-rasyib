<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class presensiController extends Controller
{
    public function create()
    {
        $hariini = date("Y-m-d");
        $nuptk = Auth::guard('pegawai')->user()->nuptk;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nuptk', $nuptk)->count();
        return view('presensi.create', compact('cek'));
    }

    public function store(Request $request)
    {
        $nuptk = Auth::guard('pegawai')->user()->nuptk;
        $tgl_presensi = date('Y-m-d');
        $jam = date("H:i:s");
        $latitudekantor = -6.783004969623575;
        $longitudekantor = 110.83700346550359;
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];

        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nuptk', $nuptk)->count();

        if ($cek > 0) {
            $ket = "out";
        } else {
            $ket = "in";
        }
        $image = $request->image;
        $folderPath = "/public/uploads/absensi/";
        $formatName = $nuptk . "-" . $tgl_presensi . "-" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . "." . "png";
        $file = $folderPath . $fileName;

        if ($radius > 50) {
            echo "error|Maaf Anda Berada Diluar Radius, Jarak Anda " . $radius . " meter dari Madrasah!|radius";
        } else {
            if ($cek > 0) {
                $dataPulang = [
                    'jam_out' => $jam,
                    'foto_out' => $fileName,
                    'lokasi_out' => $lokasi
                ];
                $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nuptk', $nuptk)->update($dataPulang);
                if ($update) {
                    echo "success|Terimakasih, Hati Hati Di jalan|out";
                    Storage::put($file, $image_base64);
                } else {
                    echo "error|Maaf Gagal Absensi, Silahkan Hubungi Tim IT|out";
                }
            } else {
                $dataMasuk = [
                    'nuptk' => $nuptk,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_in' => $jam,
                    'foto_in' => $fileName,
                    'lokasi_in' => $lokasi
                ];
                $simpan = DB::table('presensi')->insert($dataMasuk);
                if ($simpan) {
                    echo "success|Terimakasih, Selamat Bekerja|in";
                    Storage::put($file, $image_base64);
                } else {
                    echo "error|Maaf Gagal Absensi, Silahkan Hubungi Tim IT|in";
                }
            }
        }

    }

    //Calculating the Distance Between 2 Coordinate Points
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editProfile()
    {
        $nuptk = Auth::guard('pegawai')->user()->nuptk;
        $pegawai = DB::table('pegawai')->where('nuptk', $nuptk)->first();
        return view('presensi.editProfile', compact('pegawai'));
    }

    public function updateProfile(Request $request)
    {
        $nuptk = Auth::guard('pegawai')->user()->nuptk;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $pegawai = DB::table('pegawai')->where('nuptk', $nuptk)->first();

        if ($request->hasFile('foto')) {
            $foto = $nuptk . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $pegawai->foto;
        }
        if (empty($request->password)) {
            $dataUpdate = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        } else {
            $dataUpdate = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        }
        $update = DB::table('pegawai')->where('nuptk', $nuptk)->update($dataUpdate);
        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/pegawai/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil di Update']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal di Update']);
        }

    }

    public function history()
    {
        $namabulan = [
            "",
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        ];
        return view('presensi.history', compact('namabulan'));
    }

    public function getHistory(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nuptk = Auth::guard('pegawai')->user()->nuptk;

        $history = DB::table('presensi')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->where('nuptk', $nuptk)
            ->orderBy('tgl_presensi')
            ->get();

        return view('presensi.gethistory', compact('history'));
    }

    public function izin()
    {
        $nuptk = Auth::guard('pegawai')->user()->nuptk;
        $dataIzin = DB::table('pengajuan_izin')->where('nuptk', $nuptk)->get();
        return view('presensi.izin', compact('dataIzin'));
    }

    public function createIzin()
    {

        return view('presensi.createIzin');
    }

    public function storeIzin(Request $request)
    {
        $nuptk = Auth::guard('pegawai')->user()->nuptk;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $dataIzin = [
            'nuptk' => $nuptk,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan
        ];

        $simpan = DB::table('pengajuan_izin')->insert($dataIzin);

        if ($simpan) {
            return redirect('/presensi/izin')->with(['success' => 'Data berhasil disimpan']);
        } else {
            return redirect('/presensi/izin')->with(['error' => 'Data gagal disimpan']);
        }
    }

    public function monitoring()
    {
        return view('presensi.monitoring');
    }

    public function getPresensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')
            ->select('presensi.*', 'nama_lengkap', 'nama_dept')
            ->join('pegawai', 'presensi.nuptk', '=', 'pegawai.nuptk')
            ->join('departemen', 'pegawai.kode_dept', '=', 'departemen.kode_dept')
            ->where('tgl_presensi', $tanggal)
            ->get();

        return view('presensi.getPresensi', compact('presensi'));
    }

    public function tampilkanpeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)
            ->join('pegawai', 'presensi.nuptk', '=', 'pegawai.nuptk')
            ->first();
        return view('presensi.showmap', compact('presensi'));
    }

    public function laporan()
    {
        $namabulan = [
            "",
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        ];
        $pegawai = DB::table('pegawai')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('namabulan', 'pegawai'));
    }

    public function cetakLaporan(Request $request)
    {
        $nuptk = $request->nuptk;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = [
            "",
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        ];
        $pegawai = DB::table('pegawai')->where('nuptk', $nuptk)
            ->join('departemen', 'pegawai.kode_dept', '=', 'departemen.kode_dept')
            ->first();

        $presensi = DB::table('presensi')
            ->where('nuptk', $nuptk)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->get();
        return view('presensi.cetakLaporan', compact('bulan', 'tahun', 'namabulan', 'pegawai', 'presensi'));
    }
}
