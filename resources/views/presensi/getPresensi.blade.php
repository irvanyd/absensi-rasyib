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
            <span>Terlambat</span>
            @else
            <span>Tepat Waktu</span>
            @endif
        </td>
    </tr>
@endforeach
