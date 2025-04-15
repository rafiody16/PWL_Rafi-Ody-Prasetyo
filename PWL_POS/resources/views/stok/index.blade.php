@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                {{-- <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-sm btn-info mt-1">
                    Import stok
                </button> --}}
                {{-- <a class="btn btn-sm btn-primary mt-1" href="{{ url('stok/create') }}">Tambah</a> --}}
                {{-- <a class="btn btn-sm btn-primary mt-1" href="{{ url('/stok/export_excel') }}"><i
                        class="fa fa-file-excel">Export stok</i></a>
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('/stok/export_pdf') }}"><i class="fa fa-file-pdf">Export
                        stok</i></a>
                <button onclick="modalAction('{{ url('stok/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah
                    Ajax</button> --}}
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
                            <select class="form-control" id="supplier_id" name="supplier_id" required>
                                <option value="">- Supplier -</option>
                                @foreach ($supplier as $item)
                                    <option value="{{ $item->supplier_id }}">{{ $item->supplier_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Supplier stok</small>
                        </div>
                        <div class="col-3">
                            <select class="form-control" id="barang_id" name="barang_id" required>
                                <option value="">- Barang -</option>
                                @foreach ($barang as $item)
                                    <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Barang stok</small>
                        </div>
                        <div class="col-3">
                            <select class="form-control" id="user_id" name="user_id" required>
                                <option value="">- User -</option>
                                @foreach ($barang as $item)
                                    <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Barang stok</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Supplier</th>
                        <th>Barang</th>
                        <th>User</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
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
            var dataStok;
            $(document).ready(function() {
                dataStok = $('#table_stok').DataTable({
                    // serverSide: true, jika ingin menggunakan server side processing
                    serverSide: true,
                    ajax: {
                        "url": "{{ url('stok/list') }}",
                        "dataType": "json",
                        "type": "POST",
                        "data": function(d) {
                            d.supplier_id = $('#supplier_id').val();
                            d.barang_id = $('#barang_id').val();
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
                            data: "supplier.supplier_nama",
                            className: "",
                            width: '10%',
                            orderable: true,
                            searchable: true
                        }, {
                            data: "barang.barang_nama",
                            className: "",
                            width: '37%',
                            orderable: true,
                            searchable: true
                        }, {
                            data: "user.nama",
                            className: "",
                            width: '14%',
                            orderable: true,
                            searchable: true
                        }, {
                            data: "stok_tanggal",
                            className: "",
                            width: '10%',
                            orderable: true,
                            searchable: true,
                        }, {
                            data: "stok_jumlah",
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

                $('#supplier_id').on('change', function() {
                    dataStok.ajax.reload();
                });
                $('#barang_id').on('change', function() {
                    dataStok.ajax.reload();
                });
            });
        </script>
    @endpush
