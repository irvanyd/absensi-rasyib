@extends('layouts.admin.tabler')

@section('content')
    <!-- header content -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Data Pegawai
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    @if (Session::get('success'))
                                        <div class="alert alert-success">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif

                                    @if (Session::get('error'))
                                        <div class="alert alert-warning">
                                            {{ Session::get('error') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <a href="#" class="btn btn-primary" id="btnTambahPegawai">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 5l0 14"></path>
                                            <path d="M5 12l14 0"></path>
                                        </svg>
                                        Tambah Data</a>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <form action="/pegawai" method="GET">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <input type="text" name="nama_pegawai" id="nama_pegawai"
                                                        class="form-control" placeholder="Nama Pegawai"
                                                        value="{{ Request('nama_pegawai') }}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <select name="kode_dept" id="kode_dept" class="form-select">
                                                        <option value="">All Departemen</option>
                                                        @foreach ($departemen as $d)
                                                            <option
                                                                {{ Request('kode_dept') == $d->kode_dept ? 'selected' : '' }}
                                                                value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <button class="input-group-text btn btn-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-search" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                            <path d="M21 21l-6 -6"></path>
                                                        </svg>
                                                        Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NUPTK</th>
                                                <th>Nama</th>
                                                <th>Jabatan</th>
                                                <th>No. HP</th>
                                                <th>Foto</th>
                                                <th>Departemen</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pegawai as $d)
                                                @php
                                                    $path = Storage::url('uploads/pegawai/' . $d->foto);
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration + $pegawai->firstItem() - 1 }}</td>
                                                    <td>{{ $d->nuptk }}</td>
                                                    <td>{{ $d->nama_lengkap }}</td>
                                                    <td>{{ $d->jabatan }}</td>
                                                    <td>{{ $d->no_hp }}</td>
                                                    <td>
                                                        @if (empty($d->foto))
                                                            <img src="{{ asset('assets/img/noprofile.jpeg') }}"
                                                                class="avatar" alt="">
                                                        @else
                                                            <img src="{{ url($path) }}" class="avatar" alt="">
                                                        @endif
                                                    </td>
                                                    <td>{{ $d->nama_dept }}</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{ $pegawai->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modal-inputpegawai" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/pegawai/store" method="POST" id="formPegawai" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
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
                                    <input type="text" value="" class="form-control" name="nuptk"
                                        id="nuptk" placeholder="NUPTK">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="nama_lengkap"
                                        id="nama_lengkap" placeholder="Nama Pegawai">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-id-badge-2" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M7 12h3v4h-3z"></path>
                                            <path
                                                d="M10 6h-6a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h16a1 1 0 0 0 1 -1v-12a1 1 0 0 0 -1 -1h-6">
                                            </path>
                                            <path
                                                d="M10 3m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v3a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z">
                                            </path>
                                            <path d="M14 16h2"></path>
                                            <path d="M14 12h4"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="jabatan"
                                        id="jabatan" placeholder="Jabatan">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-phone"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path
                                                d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2">
                                            </path>
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="no_hp"
                                        id="no_hp" placeholder="Nomor Handphone">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <input type="file" name="foto" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <select name="kode_dept" id="kode_dept" class="form-select">
                                    <option value="">All Departemen</option>
                                    @foreach ($departemen as $d)
                                        <option {{ Request('kode_dept') == $d->kode_dept ? 'selected' : '' }}
                                            value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="co-12">
                                <div class="form-group">
                                    <button class="btn btn-primary w-100">
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
                                        Simpan</button>
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
            $("#btnTambahPegawai").click(function() {
                $("#modal-inputpegawai").modal("show");
            });
        });

        $(function() {
            $("#formPegawai").submit(function() {
                var nuptk = $("#nuptk").val();
                var nama_lengkap = $("#nama_lengkap").val();
                var jabatan = $("#jabatan").val();
                var no_hp = $("#no_hp").val();
                var kode_dept = $("formPegawai").find("#kode_dept").val();
                if (nuptk == "") {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'NUPTK harus diisi',
                        icon: 'warning',
                        confirmButtonText: 'ok'
                    }).then((result) => {
                        $("#nuptk").focus();
                    });
                    return false;
                } else if (nama_lengkap == "") {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Nama Pewagai harus diisi',
                        icon: 'warning',
                        confirmButtonText: 'ok'
                    }).then((result) => {
                        $("#nama_lengkap").focus();
                    });
                    return false;
                } else if (jabatan == "") {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Jabatan harus diisi',
                        icon: 'warning',
                        confirmButtonText: 'ok'
                    }).then((result) => {
                        $("#jabatan").focus();
                    });
                    return false;
                } else if (no_hp == "") {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Nomor Handphone harus diisi',
                        icon: 'warning',
                        confirmButtonText: 'ok'
                    }).then((result) => {
                        $("#no_hp").focus();
                    });
                    return false;
                } 
            });
        });
    </script>
@endpush
