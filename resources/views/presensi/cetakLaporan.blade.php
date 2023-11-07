<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>A4</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: A4
        }

        .format1 {
            font-size: 14px;
            line height: 1.5em;
        }

        .format2 {
            font-size: 18px;
            font-weight: bolder;
            line-height: 1.5em;
        }

        .format3 {
            font-size: 15px;
            font-weight: bold;
            line-height: 1.5em;
        }

        .tabelPegawai {
            margin-top: 20px;
        }

        .tabelPegawai td {
            padding: 5px;
        }

        .tabelPresensi {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .tabelPresensi tr th {
            border: 1px solid #131212;
            padding: 8px;
            background-color: #f1f1f1;
        }

        .tabelPresensi tr td {
            border: 1px solid #131212;
            padding: 5px;
        }

        .foto {
            width: 40px;
            height: 30px;
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4">

    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

        <table style="width: 100%">
            <tr>
                <td style="width: 30px">
                    <img src="{{ asset('assets/img/logomaarif.png') }}" width="120" height="90" alt="">
                </td>
                <td>
                    <center>
                        <article class="format3">
                            LAPORAN PRESENSI GURU DAN STAFF<br>
                            PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}
                        </article>
                        <article class="format2">
                            MI NU RAUDLATUS SHIBYAN 01
                        </article>
                        <article class="format1">
                            Jl. Dewi Sartika No. 252 Peganjaran Gg. 1 Bae Kudus. Telp. 0858 6618 5855<br>
                            Email : minuraudlatusshibyan01@gmail.com
                        </article>
                    </center>
                </td>
                <td style="width: 30px">
                    <img src="{{ asset('assets/img/logomi.png') }}" width="90" height="90" alt="">
                </td>
            </tr>
        </table>
        <table class="tabelPegawai">
            <hr>
            <tr>
                <td rowspan="6">
                    @php
                        $path = Storage::url('uploads/pegawai/' . $pegawai->foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="" width="110" height="150">
                </td>
            </tr>
            <tr>
                <td>Nama Guru/Staff</td>
                <td>:</td>
                <td>{{ $pegawai->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $pegawai->jabatan }}</td>
            </tr>
            <tr>
                <td>Departemen</td>
                <td>:</td>
                <td>{{ $pegawai->nama_dept }}</td>
            </tr>
            <tr>
                <td>Nomor Handphone</td>
                <td>:</td>
                <td>{{ $pegawai->no_hp }}</td>
            </tr>
        </table>
        <table class="tabelPresensi">
            <hr>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Foto</th>
                <th>Jam Pulang</th>
                <th>Foto</th>
                <th>Keterangan</th>
            </tr>
            @foreach ($presensi as $d)
                @php
                    $path_in = Storage::url('uploads/absensi/' . $d->foto_in);
                    $path_out = Storage::url('uploads/absensi/' . $d->foto_out);
                @endphp
                <tr>
                    <td>{{ $loop->iteration . "."}}</td>
                    <td>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
                    <td>{{ $d->jam_in }}</td>
                    <td><img src="{{ url($path_in) }}" alt="" class="foto"></td>
                    <td>{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</td>
                    <td><img src="{{ url($path_out) }}" alt="" class="foto"></td>
                    <td>
                        @if ($d->jam_in > '07:00')
                            Terlambat
                        @else
                            Tepat Waktu
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>

    </section>

</body>

</html>
