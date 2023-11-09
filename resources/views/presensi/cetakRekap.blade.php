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
            font-size: 10px;
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

<body class="A4 landscape">
    @php
        function selisih($jam_masuk, $jam_keluar)
        {
            [$h, $m, $s] = explode(':', $jam_masuk);
            $dtAwal = mktime($h, $m, $s, '1', '1', '1');
            [$h, $m, $s] = explode(':', $jam_keluar);
            $dtAkhir = mktime($h, $m, $s, '1', '1', '1');
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode('.', $totalmenit / 60);
            $sisamenit = $totalmenit / 60 - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . ' j ' . round($sisamenit2) . ' m ';
        }
    @endphp

    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

        <table style="width: 100%">
            <tr>
                <td style="width: 160px"></td>
                <td style="width: 30px">
                    <img src="{{ asset('assets/img/logomaarif.png') }}" width="120" height="90" alt="">
                </td>
                <td>
                    <center>
                        <article class="format3">
                            REKAP PRESENSI GURU DAN STAFF<br>
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
                <td style="width: 160px"></td>
            </tr>
        </table>
        <hr>
        <table class="tabelPresensi">
            <tr>
                <th rowspan="2">NUPTK</th>
                <th rowspan="2">Nama Pegawai</th>
                <th colspan="31">Tanggal</th>
                <th rowspan="2">TH</th>
                <th rowspan="2">TT</th>
            </tr>
            <tr>
                <?php
                for ($day=1; $day<=31 ; $day++) { 
                ?>
                <th>{{ $day }}</th>
                <?php     
                }
                ?>
            </tr>
            @foreach ($rekap as $d)
                <tr style="text-align: center">
                    <td>{{ $d->nuptk }}</td>
                    <td>{{ $d->nama_lengkap }}</td>
                    @php
                        $totalHadir = 0;
                        $totalTerlambat = 0;
                    @endphp
                    @for ($day = 1; $day <= 31; $day++)
                        <td style="color: {{ $d->{'tgl_' . $day} == null ? 'red' : 'black' }}">
                            @if ($d->{'tgl_' . $day} == null)
                                &#10006; <!-- Silang (red X) -->
                            @else
                                &#10004; <!-- Ceklis (checkmark) -->
                                @if ($d->{'tgl_' . $day} >= '07:00:00')
                                    &#33; <!-- Warning (checkmark) -->
                                    <!-- Contoh batas terlambat pukul 07:00:00 -->
                                    @php
                                        $totalTerlambat++;
                                    @endphp
                                @endif
                                @php
                                    $totalHadir++;
                                @endphp
                            @endif
                        </td>
                    @endfor
                    <td>{{ $totalHadir }}</td>
                    <td>{{ $totalTerlambat }}</td>
                </tr>
            @endforeach
        </table>
        <table class="format1" style="margin-top: 10px">
            <tr>
                <td>Note : &#10004; = Tepat Waktu | &#10004;&#33; = Terlambat | &#10006; = Tidak Hadir</td>
            </tr>
        </table>
        <table width="100%" style="margin-top:150px; margin-left:30px">
            <tr>
                <td>Kudus, {{ date('d-m-Y') }}</td>
            </tr>
            <tr>
                <td style="text-align:left; vertical-align:bottom" height="100px">
                    <u>Zuzron Hadi, S.Pd.I.</u><br>
                    <i><b>Kepala Madrasah</b></i>
                </td>
            </tr>
        </table>
    </section>

</body>

</html>
