<form action="{{ route('profile.update') }}" method="POST" id="form-edit-profile" enctype="multipart/form-data">
    @csrf
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Foto Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group text-center">
                    <img src="{{ asset('storage/profile_images/' . ($user->profile_picture ?? 'anonymous.png')) }}"
                        class="img-thumbnail mb-3"
                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                    <div>
                        <label for="profile_picture">Pilih Foto Profil Baru</label>
                        <input type="file" name="profile_picture" id="profile_picture" class="form-control-file"
                            required>
                        <small id="error-profile_picture" class="error-text form-text text-danger"></small>
                    </div>
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
    $(document).ready(function() {
        $("#form-edit-profile").validate({
            rules: {
                profile_picture: {
                    required: true,
                    extension: "jpg|jpeg|png"
                }
            },
            submitHandler: function(form) {
                var formData = new FormData(form);

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message ||
                                'Foto profil berhasil diperbarui.'
                        }).then(() => {
                            location
                                .reload(); // reload halaman agar foto baru muncul
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        $('.error-text').text('');
                        if (xhr.responseJSON?.errors) {
                            $.each(xhr.responseJSON.errors, function(key, val) {
                                $('#error-' + key).text(val[0]);
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat mengupload foto.'
                        });
                    }
                });

                return false;
            }
        });
    });
</script>
