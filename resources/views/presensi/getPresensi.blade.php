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
        return $jml_jam . ' jam ' . round($sisamenit2) . ' menit ';
    }
@endphp

@foreach ($presensi as $d)
    @php
        $foto_in = Storage::url('uploads/absensi/' . $d->foto_in);
        $foto_out = Storage::url('uploads/absensi/' . $d->foto_out);
    @endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->nuptk }}</td>
        <td>{{ $d->nama_lengkap }}</td>
        <td>{{ $d->nama_dept }}</td>
        <td>{{ $d->jam_in }}</td>
        <td>
            <img src="{{ url($foto_in) }}" class="avatar" alt="">
        </td>
        <td>{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</td>
        <td>
            @if ($d->jam_out != null)
                <img src="{{ url($foto_out) }}" class="avatar" alt="">
            @else
                <img src="{{ asset('assets/img/noprofile.jpeg') }}" class="avatar" alt="">
            @endif
        </td>
        <td>
            @if ($d->jam_in >= '07:00')
                @php
                    $jamterlambat = selisih('07:00:00', $d->jam_in);
                @endphp
                <span>Terlambat {{ $jamterlambat }}</span>
            @else
                <span>Tepat Waktu</span>
            @endif
        </td>
    </tr>
@endforeach
