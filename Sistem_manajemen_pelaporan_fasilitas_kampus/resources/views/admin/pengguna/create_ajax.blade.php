<form action="{{ route('admin.pengguna.ajaxstore') }}" method="POST" id="form-tambah">
    @csrf
    <div class="card-header">
    </div>
    <div class="card-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-4"><label>Username</label></div>
                <div class="col-md-8">
                    <input type="text" name="username" class="form-control" placeholder="Username">
                </div>
                <div class="col-md-4"><label>Nama</label></div>
                <div class="col-md-8">
                    <input type="text" name="nama" class="form-control" placeholder="Nama">
                </div>

                <div class="col-md-4"><label>Email</label></div>
                <div class="col-md-8">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                </div>

                {{-- <div class="col-md-4"><label>Identitas</label></div>
                <div class="col-md-8">
                    <input type="text" name="identitas" class="form-control" placeholder="NIP/NIM/NIDN">
                </div> --}}

                <div class="col-md-4"><label>Peran</label></div>
                <div class="col-md-8">
                    <select name="peran" class="form-control">
                        <option value="admin">Admin</option>
                        <option value="sarpras">Sarpras</option>
                        <option value="teknisi">Teknisi</option>
                        <option value="pelapor">Dosen</option>
                        <option value="pelapor">Mahasiswa</option>
                        <option value="pelapor">Tendik</option>
                    </select>
                </div>

                <div class="col-md-4"><label>Password</label></div>
                <div class="col-md-8">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                </div>

                <div class="col-12 d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        $("#form-tambah").validate({
            rules: {
                username: { required: true, minlength: 3 },
                nama: { required: true, minlength: 3 },
                email: { required: true, email: true },
                // identitas: { required: true },
                peran: { required: true },
                password: { required: true, minlength: 6 }
            },
            submitHandler: function (form, event) {
                event.preventDefault(); // mencegah submit default, kalau event tersedia
                $.ajax({
                    url: form.action,
                    method: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status) {
                            $('#modalTambahPengguna').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                // Tambahkan delay kecil sebelum reload
                                setTimeout(() => {
                                    location.reload();
                                }, 300); // 300ms delay agar data sudah tersimpan ke DB
                            });

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    }
                });
                return false;  // pastikan ada ini supaya submit form dibatalkan
            }
        });
    });
</script>