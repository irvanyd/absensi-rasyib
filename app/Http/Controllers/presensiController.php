<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan_izin;
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
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        return view('presensi.create', compact('cek', 'lok_kantor'));
    }

    public function store(Request $request)
    {
        $nuptk = Auth::guard('pegawai')->user()->nuptk;
        $tgl_presensi = date('Y-m-d');
        $jam = date("H:i:s");
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        $lok = explode(",", $lok_kantor->lokasi_kantor);
        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];
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

        if ($radius > $lok_kantor->radius) {
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
            ->orderBy('tgl_presensi')
            ->get();
        return view('presensi.cetakLaporan', compact('bulan', 'tahun', 'namabulan', 'pegawai', 'presensi'));
    }

    public function rekap()
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

        return view('presensi.rekap', compact('namabulan'));
    }

    public function cetakRekap(Request $request)
    {
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

        $rekapQuery = DB::table('presensi')
            ->select('presensi.nuptk', 'nama_lengkap');

        for ($day = 1; $day <= 31; $day++) {
            $rekapQuery->addSelect(
                DB::raw('MAX(IF(DAY(tgl_presensi) = ' . $day . ', CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_' . $day)
            );
        }

        $rekap = $rekapQuery
            ->join('pegawai', 'presensi.nuptk', '=', 'pegawai.nuptk')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->groupBy('presensi.nuptk', 'nama_lengkap')
            ->get();

        if (isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            // fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            // mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Presensi Pegawai Rasyib $time.xls");
        }

        return view('presensi.cetakRekap', compact('bulan', 'tahun', 'namabulan', 'rekap'));
    }

    public function perizinan(Request $request)
    {
        $query = Pengajuan_izin::query();
        $query->select('id', 'tgl_izin', 'pengajuan_izin.nuptk', 'nama_lengkap', 'jabatan', 'status', 'status_pengajuan', 'keterangan');
        $query->join('pegawai', 'pengajuan_izin.nuptk', '=', 'pegawai.nuptk');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_izin', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nuptk)) {
            // $query->where('pengajuan_izin.nuptk', $request->nuptk);
            $query->where('pengajuan_izin.nuptk', 'like', '%' . $request->nuptk . '%');
        }

        if (!empty($request->nama_pegawai)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_pegawai . '%');
        }

        if ($request->status_pengajuan === '0'|| $request->status_pengajuan === '1'|| $request->status_pengajuan === '2') {
            $query->where('status_pengajuan', $request->status_pengajuan);
        } 
        $query->orderBy('tgl_izin', 'desc');
        $perizinan = $query->paginate(5);
        $perizinan->appends($request->all());
        return view('presensi.perizinan', compact('perizinan'));
    }

    public function accPerizinan(Request $request)
    {
        $status_approved = $request->status_pengajuan;
        $id_perizinan_form = $request->id_perizinan_form;
        $update = DB::table('pengajuan_izin')->where('id', $id_perizinan_form)->update([
            'status_pengajuan' => $status_approved
        ]);

        if ($update) {
            return Redirect::back()->with(['success' => 'Data berhasil diupdate']);
        } else {
            return Redirect::back()->with(['warning' => 'Data gagal diupdate']);
        }
    }

    public function batalkanPerizinan($id)
    {
        $update = DB::table('pengajuan_izin')->where('id', $id)->update([
            'status_pengajuan' => 0
        ]);

        if ($update) {
            return Redirect::back()->with(['success' => 'Data berhasil diupdate']);
        } else {
            return Redirect::back()->with(['warning' => 'Data gagal diupdate']);
        }
    }

    public function cekPengajuan(Request $request)
    {
        $tgl_izin = $request->tgl_izin;
        $nuptk = Auth::guard('pegawai')->user()->nuptk;
        $cek = DB::table('pengajuan_izin')->where('nuptk', $nuptk)->where('tgl_izin', $tgl_izin)->count();
        return $cek;
    }

}