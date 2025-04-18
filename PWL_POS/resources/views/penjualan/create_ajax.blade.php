<form action="{{ url('penjualan/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>User</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">- Pilih User -</option>
                        @foreach ($user as $l)
                            <option value="{{ $l->user_id }}">{{ $l->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-kategori_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Pembeli</label>
                    <input value="" type="text" name="pembeli" id="pembeli" class="form-control" required>
                    <small id="error-pembeli" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kode Penjualan</label>
                    <input value="" type="text" name="penjualan_kode" id="penjualan_kode" class="form-control"
                        required>
                    <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Penjualan</label>
                    <input value="" type="date" name="penjualan_tanggal" id="penjualan_tanggal"
                        class="form-control" required>
                    <small id="error-stok_tanggal" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Detail Barang</label>
                    <div id="detail-container">
                        <div class="row detail-item mb-2">
                            <div class="col-md-4">
                                <select name="barang_id[]" id="barang_id[]" class="form-control" required>
                                    <option value="">- Pilih Barang -</option>
                                    @foreach ($barang as $l)
                                        <option value="{{ $l->barang_id }}">{{ $l->barang_nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="harga[]" id="harga[]" class="form-control"
                                    placeholder="Harga" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="jumlah[]" id="jumlah[]" class="form-control"
                                    placeholder="Jumlah" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-remove">Hapus</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-detail" class="btn btn-sm btn-secondary mt-2">+ Tambah
                        Barang</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    const dataBarang = @json($barang);
    $(document).ready(function() {
        $('#add-detail').on('click', function() {
            let detailRow = `
            <div class="row detail-item mb-2">
                <div class="col-md-4">
                    <select name="barang_id[]" id="barang_id[]" class="form-control" required>
                        <option value="">- Pilih Barang -</option>
                        @foreach ($barang as $l)
                            <option value="{{ $l->barang_id }}">{{ $l->barang_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="harga[]" id="harga[]" class="form-control" placeholder="Harga" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="jumlah[]" id="jumlah[]" class="form-control" placeholder="Jumlah" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-remove">Hapus</button>
                </div>
            </div>`;
            $('#detail-container').append(detailRow);
        });

        $(document).on('change', 'select[name="barang_id[]"]', function() {
            const selectedId = $(this).val();
            const row = $(this).closest('.detail-item');
            const barang = dataBarang.find(b => b.barang_id == selectedId);

            if (barang) {
                row.find('input[name="harga[]"]').val(barang.harga_jual);
            }
        });


        $(document).on('click', '.btn-remove', function() {
            $(this).closest('.detail-item').remove();
        });
        $("#form-tambah").validate({
            rules: {
                'user_id': {
                    required: true,
                    number: true
                },
                'pembeli': {
                    required: true,
                    maxlength: 50
                },
                'penjualan_kode': {
                    required: true,
                    maxlength: 20
                },
                'penjualan_tanggal': {
                    required: true,
                    date: true
                },
                'barang_id[]': {
                    required: true
                },
                'harga[]': {
                    required: true,
                    number: true
                },
                'jumlah[]': {
                    required: true,
                    number: true
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataPenjualan.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
