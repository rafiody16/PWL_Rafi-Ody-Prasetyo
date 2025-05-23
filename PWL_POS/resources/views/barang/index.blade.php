@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/barang/import') }}')" class="btn btn-sm btn-info mt-1">
                    Import Barang
                </button>
                {{-- <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a> --}}
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('/barang/export_excel') }}"><i
                        class="fa fa-file-excel">Export Barang</i></a>
                <a class="btn btn-sm btn-warning mt-1" href="{{ url('/barang/export_pdf') }}"><i
                        class="fa fa-file-pdf">Export Barang</i></a>
                <button onclick="modalAction('{{ url('barang/create_ajax') }}')"
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
                            <select class="form-control" id="kategori_id" name="kategori_id" required>
                                <option value="">- Semua -</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Kategori Barang</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>kategori Barang</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
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
            var dataBarang;
            $(document).ready(function() {
                dataBarang = $('#table_barang').DataTable({
                    // serverSide: true, jika ingin menggunakan server side processing
                    serverSide: true,
                    ajax: {
                        "url": "{{ url('barang/list') }}",
                        "dataType": "json",
                        "type": "POST",
                        "data": function(d) {
                            d.kategori_id = $('#kategori_id').val();
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
                            data: "barang_kode",
                            className: "",
                            // orderable: true, jika ingin kolom ini bisa diurutkan
                            width: '10%',
                            orderable: true,
                            // searchable: true, jika ingin kolom ini bisa dicari
                            searchable: true
                        }, {
                            data: "barang_nama",
                            className: "",
                            width: '37%',
                            orderable: true,
                            searchable: true
                        }, {
                            // mengambil data kategori hasil dari ORM berelasi
                            data: "kategori.kategori_nama",
                            className: "",
                            width: '14%',
                            orderable: false,
                            searchable: false
                        }, {
                            data: "harga_beli",
                            className: "",
                            width: '10%',
                            orderable: true,
                            searchable: true,
                            render: function(data, type, row) {
                                return 'Rp. ' + new Intl.NumberFormat('id-ID').format(data);
                            }
                        }, {
                            data: "harga_jual",
                            className: "",
                            width: '10%',
                            orderable: true,
                            searchable: true,
                            render: function(data, type, row) {
                                return 'Rp. ' + new Intl.NumberFormat('id-ID').format(data);
                            }
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

                $('#kategori_id').on('change', function() {
                    dataBarang.ajax.reload();
                });
            });
        </script>
    @endpush
