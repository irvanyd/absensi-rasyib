@extends('layouts.admin.tabler')

@section('content')
    <style>
        .badge {
            color: white;
        }
    </style>
    <!-- header content -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Data Izin / Sakit
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <form action="/presensi/perizinan" method="GET" autocomplete="off">
                        <div class="row">
                            <div class="col-6">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-calendar-event" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path
                                                d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                                            </path>
                                            <path d="M16 3l0 4"></path>
                                            <path d="M8 3l0 4"></path>
                                            <path d="M4 11l16 0"></path>
                                            <path d="M8 15h2v2h-2z"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="{{ Request('dari') }}" class="form-control" name="dari" id="dari"
                                        placeholder="Dari">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-calendar-event" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path
                                                d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                                            </path>
                                            <path d="M16 3l0 4"></path>
                                            <path d="M8 3l0 4"></path>
                                            <path d="M4 11l16 0"></path>
                                            <path d="M8 15h2v2h-2z"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="{{ Request('sampai') }}" class="form-control" name="sampai" id="sampai"
                                        placeholder="Sampai">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-id"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path
                                                d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z">
                                            </path>
                                            <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                            <path d="M15 8l2 0"></path>
                                            <path d="M15 12l2 0"></path>
                                            <path d="M7 16l10 0"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="{{ Request('nuptk') }}" class="form-control" name="nuptk" id="nuptk"
                                        placeholder="NUPTK">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="{{ Request('nama_pegawai') }}" class="form-control" name="nama_pegawai"
                                        id="nama_pegawai" placeholder="Nama Pegawai">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <select name="status_pengajuan" id="status_pengajuan" class="form-select">
                                        <option value="">Pilih Status</option>
                                        <option value="0" {{ Request('status_pengajuan') === '0' ? 'selected' : '' }}>Pending</option>
                                        <option value="1" {{ Request('status_pengajuan') == 1 ? 'selected' : '' }}>Disetujui</option>
                                        <option value="2" {{ Request('status_pengajuan') == 2 ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                            <path d="M21 21l-6 -6"></path>
                                         </svg>
                                         Cari Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>NUPTK</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Pengajuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perizinan as $d)
                                <tr>
                                    <td>{{ $loop->iteration + $perizinan->firstItem() - 1 }}</td>
                                    <td>{{ date('d-m-Y', strtotime($d->tgl_izin)) }}</td>
                                    <td>{{ $d->nuptk }}</td>
                                    <td>{{ $d->nama_lengkap }}</td>
                                    <td>{{ $d->jabatan }}</td>
                                    <td>{{ $d->status == 'i' ? 'Izin' : 'Sakit' }}</td>
                                    <td>{{ $d->keterangan }}</td>
                                    <td>
                                        @if ($d->status_pengajuan == 1)
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($d->status_pengajuan == 2)
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($d->status_pengajuan == 0)
                                            <a href="#" class="btn btn-sm btn-primary" id="editPengajuan"
                                                id_perizinan="{{ $d->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-external-link" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6">
                                                    </path>
                                                    <path d="M11 13l9 -9"></path>
                                                    <path d="M15 4h5v5"></path>
                                                </svg>
                                                Edit
                                            </a>
                                        @else
                                            <a href="/presensi/{{ $d->id }}/batalkanPerizinan"
                                                class="btn btn-sm btn-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-square-rounded-x" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M10 10l4 4m0 -4l-4 4"></path>
                                                    <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z">
                                                    </path>
                                                </svg>
                                                Batalkan
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $perizinan->links() }}
                </div>
            </div>
        </div>
    </div>
    {{-- modal Edit Perizinan --}}
    <div class="modal modal-blur fade" id="modal-perizinan" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Izin / Sakit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/presensi/accPerizinan" method="POST">
                        @csrf
                        <input type="hidden" id="id_perizinan_form" name="id_perizinan_form">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <select name="status_pengajuan" id="status_pengajuan" class="form-select">
                                        <option value="1">Disetujui</option>
                                        <option value="2">Ditolak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <button class="btn btn-primary w-100" type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M10 14l11 -11"></path>
                                            <path
                                                d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5">
                                            </path>
                                        </svg>
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            $("#editPengajuan").click(function(e) {
                e.preventDefault();
                var id_perizinan = $(this).attr("id_perizinan");
                // alert(id_perizinan);
                $("#id_perizinan_form").val(id_perizinan);
                $("#modal-perizinan").modal("show");
            });

            $("#dari, #sampai").datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endpush
