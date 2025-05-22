@extends('layouts.template')

@section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Formulir Laporan</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @csrf
                            <form class="form" method="POST" action="{{ url('/pelapor/laporan') }}" id="form-laporan"
                                enctype="multipart/form-data" data-parsley-validate>
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mandatory">
                                            <label for="fasilitas_id" class="form-label">Fasilitas</label>
                                            <select id="fasilitas_id" class="form-select" name="fasilitas_id"
                                                data-parsley-required="true">
                                                <option value="">Pilih Fasilitas</option>
                                                @foreach ($fasilitas as $fasilitas)
                                                    <option value="{{ $fasilitas->id }}">{{ $fasilitas->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="foto" class="form-label">Foto Kerusakan</label>
                                            <input type="file" id="foto" class="form-control" name="foto"
                                                accept="image/*">
                                            <small class="text-muted">Format: jpeg, png, jpg, gif (max: 2MB)</small>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group mandatory">
                                            <label for="deskripsi" class="form-label">Deskripsi Kerusakan</label>
                                            <textarea id="deskripsi" class="form-control" name="deskripsi" rows="3"
                                                placeholder="Jelaskan kerusakan yang terjadi" data-parsley-required="true"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mandatory mt-3">
                                            <label for="tingkat_urgensi" class="form-label">Tingkat Urgensi</label>
                                            <select id="tingkat_urgensi" name="tingkat_urgensi" class="form-select"
                                                data-parsley-required="true">
                                                <option value="">Pilih tingkat kerusakan</option>
                                                <option value="rendah">Rendah</option>
                                                <option value="sedang">Sedang</option>
                                                <option value="tinggi">Tinggi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">
                                            Kirim Laporan
                                        </button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/parsley.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#form-laporan").validate({
                rules: {
                    fasilitas_id: {
                        required: true
                    },
                    deskripsi: {
                        required: true
                    },
                    tingkat_urgensi: {
                        required: true
                    },
                    foto: {
                        required: true,
                        extension: "jpg|jpeg|png|gif",
                        filesize: 2 * 1024 * 1024 // 2MB
                    },
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function (response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                window.location.href = "{{ url('/pelapor/laporan_saya') }}";
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function (prefix, val) {
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
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endpush
