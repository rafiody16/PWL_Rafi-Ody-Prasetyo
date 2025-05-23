@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/penjualan/import') }}')" class="btn btn-sm btn-info mt-1">
                    Import penjualan
                </button>
                {{-- <a class="btn btn-sm btn-primary mt-1" href="{{ url('penjualan/create') }}">Tambah</a> --}}
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('/penjualan/export_excel') }}"><i
                        class="fa fa-file-excel">Export penjualan</i></a>
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('/penjualan/export_pdf') }}"><i
                        class="fa fa-file-pdf">Export
                        penjualan</i></a>
                <button onclick="modalAction('{{ url('penjualan/create_ajax') }}')"
                    class="btn btn-sm btn-success mt-1">Tambah</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            {{-- Filtering --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="user_id" name="user_id" required>
                                <option value="">- User -</option>
                                @foreach ($user as $item)
                                    <option value="{{ $item->user_id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">User penjualan</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Pembeli</th>
                        <th>Penjualan Kode</th>
                        <th>Penjaualan Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog"
            databackdrop="static"data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    @endsection

    @push('css')
    @endpush

    @push('js')
        <script>
            function modalAction(url = '') {
                $('#myModal').load(url, function() {
                    $('#myModal').modal('show');
                });
            }
            var dataPenjualan;
            $(document).ready(function() {
                dataPenjualan = $('#table_penjualan').DataTable({
                    // serverSide: true, jika ingin menggunakan server side processing
                    serverSide: true,
                    ajax: {
                        "url": "{{ url('penjualan/list') }}",
                        "dataType": "json",
                        "type": "POST",
                        "data": function(d) {
                            d.user_id = $('#user_id').val();
                        }
                    },
                    columns: [{
                            // nomor urut dari laravel datatable addIndexColumn()
                            data: "DT_RowIndex",
                            className: "text-center",
                            width: '5%',
                            orderable: false,
                            searchable: false
                        }, {
                            data: "user.nama",
                            className: "",
                            width: '15%',
                            orderable: true,
                            searchable: true
                        }, {
                            data: "pembeli",
                            className: "",
                            width: '20%',
                            orderable: true,
                            searchable: true
                        }, {
                            data: "penjualan_kode",
                            className: "",
                            width: '14%',
                            orderable: true,
                            searchable: true
                        }, {
                            data: "penjualan_tanggal",
                            className: "",
                            width: '10%',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            data: "aksi",
                            className: "",
                            width: '14%',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
                $('#user_id').on('change', function() {
                    dataPenjualan.ajax.reload();
                });
            });
        </script>
    @endpush
