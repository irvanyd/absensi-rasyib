@extends('layouts.admin.tabler')

@section('content')
    
    <!-- header content -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Monitoring Presensi
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
                                    <div class="input-icon mb-3">
                                        <span class="input-icon-addon">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-calendar-search" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path
                                                    d="M11.5 21h-5.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4.5">
                                                </path>
                                                <path d="M16 3v4"></path>
                                                <path d="M8 3v4"></path>
                                                <path d="M4 11h16"></path>
                                                <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                                                <path d="M20.2 20.2l1.8 1.8"></path>
                                            </svg>
                                        </span>
                                        <input type="text" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}"
                                            class="form-control" placeholder="Tanggal Presensi" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No .</th>
                                                <th>NUPTK</th>
                                                <th>Nama Pegawai</th>
                                                <th>Departemen</th>
                                                <th>Jam Masuk</th>
                                                <th>Foto</th>
                                                <th>Jam Pulang</th>
                                                <th>Foto</th>
                                                <th>Keterangan</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadPresensi">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal edit pegawai --}}
    <div class="modal modal-blur fade" id="modal-tampilkanpeta" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lokasi Presensi User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="loadmap">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            $("#tanggal").datepicker({
                autoclose: true
                , todayHighlight: true
                , format: 'yyyy-mm-dd'
            });

            function loadPresensi() {
                var tanggal = $("#tanggal").val();
                $.ajax({
                    type: 'POST'
                    , url: '/getPresensi'
                    , data: {
                        _token: "{{ csrf_token() }}"
                        , tanggal: tanggal
                    }
                    , cache: false
                    , success: function(respond) {
                        $("#loadPresensi").html(respond);
                    }
                });
            }
            $("#tanggal").change(function(e) {
                loadPresensi();
            });

            loadPresensi();
        });
    </script>
@endpush
