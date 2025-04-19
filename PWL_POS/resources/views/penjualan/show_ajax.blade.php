@isset($penjualan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-tools">
                    <a class="btn btn-sm btn-primary mt-1"
                        href="{{ url('/penjualan/' . $penjualan->penjualan_id . '/export_exceldtl') }}"><i
                            class="fa fa-file-excel">Export Detail Penjualan</i></a>
                    <a class="btn btn-sm btn-warning mt-1"
                        href="{{ url('/penjualan/' . $penjualan->penjualan_id . '/export_pdfdtl') }}"><i
                            class="fa fa-file-pdf">Export Detail Penjualan</i></a>
                </div>
                <br>
                <table class="table table-sm table-bordered">
                    <tr>
                        <th class="text-left col-3">ID :</th>
                        <td class="col-9">{{ $penjualan->penjualan_id }}</td>
                    </tr>
                    <tr>
                        <th class="text-left col-3">User :</th>
                        <td class="col-9">{{ $penjualan->user->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-left col-3">Kode Penjualan :</th>
                        <td class="col-9">{{ $penjualan->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th class="text-left col-3">Tanggal Penjualan :</th>
                        <td class="col-9">{{ $penjualan->penjualan_tanggal }}</td>
                    </tr>
                </table>
                <table class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penjualan->penjualanDetail as $index => $p)
                            <tr>
                                <td>{{ 1 + $index++ }}</td>
                                <td>{{ $p->barang->barang_nama }}</td>
                                <td>{{ $p->jumlah }}</td>
                                <td>{{ $p->harga }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Kembali</button>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/stok') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@endisset
