@extends('layouts.template')
@section('title', 'Laporakan Kerusakan')

@section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-1">
                        <h4 class="card-title mb-0">Formulir Laporan</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body p-4 pt-1">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <form class="form" method="POST" action="{{ url('/pelapor/laporan') }}" id="form-laporan"
                                enctype="multipart/form-data" data-parsley-validate>
                                @csrf
                                <div class="row">

                                    <div class="col-12">
                                        <div class="form-group mandatory mt-3">
                                            <label for="gedung_id" class="form-label">Nama</label>
                                            <input type="text" class="form-control" id="disabledInput"
                                                placeholder="{{ $user->nama }}" disabled="">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group mandatory mt-3">
                                            <label for="gedung_id" class="form-label">NIM/NIP/NIPSN</label>
                                            <input type="text" class="form-control" id="disabledInput"
                                                placeholder="{{ $user->username }}" disabled="">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group mandatory mt-3">
                                            <label for="gedung_id" class="form-label">Gedung</label>
                                            <select id="gedung_id" name="gedung_id" class="form-select"
                                                data-parsley-required="true">
                                                <option value="">Pilih lokasi gedung</option>
                                                @foreach ($gedung as $g)
                                                    <option value="{{ $g->gedung_id }}">{{ $g->gedung_nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mandatory">
                                            <label for="lantai_id" class="form-label">Lantai</label>
                                            <select id="lantai_id" class="form-select" name="lantai_id"
                                                data-parsley-required="true" disabled>
                                                <option value="">Pilih Gedung terlebih dahulu</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mandatory">
                                            <label for="ruangan_id" class="form-label">Ruangan</label>
                                            <select id="ruangan_id" class="form-select" name="ruangan_id"
                                                data-parsley-required="true" disabled>
                                                <option value="">Pilih Lantai terlebih dahulu</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mandatory">
                                            <label for="fasilitas_id" class="form-label">Fasilitas</label>
                                            <select id="fasilitas_id" class="form-select" name="fasilitas_id"
                                                data-parsley-required="true" disabled>
                                                <option value="">Pilih ruangan terlebih dahulu</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mandatory">
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
    <script>
        $(document).ready(function() {
            // Gedung change handler
            $('#gedung_id').change(function() {
                var gedungId = $(this).val();
                var lantaiSelect = $('#lantai_id');
                var ruanganSelect = $('#ruangan_id');
                var fasilitasSelect = $('#fasilitas_id');

                // Reset downstream selects
                ruanganSelect.empty().append('<option value="">Pilih Lantai terlebih dahulu</option>').prop(
                    'disabled', true);
                fasilitasSelect.empty().append('<option value="">Pilih Ruangan terlebih dahulu</option>')
                    .prop('disabled', true);

                if (gedungId) {
                    $.ajax({
                        url: '/pelapor/laporan/get_lantai_by_gedung',
                        type: 'GET',
                        data: {
                            gedung_id: gedungId
                        },
                        success: function(data) {
                            lantaiSelect.empty().append(
                                '<option value="">Pilih Lantai</option>');
                            $.each(data, function(key, value) {
                                lantaiSelect.append('<option value="' + value
                                    .lantai_id + '">' + value.lantai_nama +
                                    '</option>');
                            });
                            lantaiSelect.prop('disabled', false);
                        },
                        error: function() {
                            lantaiSelect.empty().append(
                                '<option value="">Gagal memuat lantai</option>');
                        }
                    });
                } else {
                    lantaiSelect.empty().append('<option value="">Pilih Gedung terlebih dahulu</option>')
                        .prop('disabled', true);
                }
            });

            // Lantai change handler
            $('#lantai_id').change(function() {
                var lantaiId = $(this).val();
                var ruanganSelect = $('#ruangan_id');
                var fasilitasSelect = $('#fasilitas_id');

                // Reset downstream selects
                fasilitasSelect.empty().append('<option value="">Pilih Ruangan terlebih dahulu</option>')
                    .prop('disabled', true);

                if (lantaiId) {
                    $.ajax({
                        url: '/pelapor/laporan/get_ruangan_by_lantai',
                        type: 'GET',
                        data: {
                            lantai_id: lantaiId
                        },
                        success: function(data) {
                            ruanganSelect.empty().append(
                                '<option value="">Pilih Ruangan</option>');
                            $.each(data, function(key, value) {
                                ruanganSelect.append('<option value="' + value
                                    .ruangan_id + '">' + value.ruangan_nama +
                                    '</option>');
                            });
                            ruanganSelect.prop('disabled', false);
                        },
                        error: function() {
                            ruanganSelect.empty().append(
                                '<option value="">Gagal memuat ruangan</option>');
                        }
                    });
                } else {
                    ruanganSelect.empty().append('<option value="">Pilih Lantai terlebih dahulu</option>')
                        .prop('disabled', true);
                }
            });

            // Ruangan change handler
            $('#ruangan_id').change(function() {
                var ruanganId = $(this).val();
                var fasilitasSelect = $('#fasilitas_id');

                if (ruanganId) {
                    $.ajax({
                        url: '/pelapor/laporan/get_fasilitas_by_ruangan',
                        type: 'GET',
                        data: {
                            ruangan_id: ruanganId
                        },
                        success: function(data) {
                            fasilitasSelect.empty().append(
                                '<option value="">Pilih Fasilitas</option>');
                            $.each(data, function(key, value) {
                                fasilitasSelect.append('<option value="' + value
                                    .fasilitas_id + '">' + value.fasilitas_nama +
                                    '</option>');
                            });
                            fasilitasSelect.prop('disabled', false);
                        },
                        error: function() {
                            fasilitasSelect.empty().append(
                                '<option value="">Gagal memuat fasilitas</option>');
                        }
                    });
                } else {
                    fasilitasSelect.empty().append(
                        '<option value="">Pilih Ruangan terlebih dahulu</option>').prop('disabled',
                        true);
                }
            });

            // Form validation and submission (existing code)
            $('#form-laporan').validate({
                ignore: 'input[type="file"]', // Abaikan file untuk validasi jQuery Validate
                errorClass: 'is-invalid',
                validClass: 'is-valid',
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    if (element.prop('type') === 'checkbox') {
                        error.insertAfter(element.next('label'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).addClass('is-valid').removeClass('is-invalid');
                }
            });

            // Submit handler
            $("#form-laporan").on('submit', function(e) {
                e.preventDefault();

                if ($(this).valid()) {
                    var form = this;
                    var formData = new FormData(form); // Handle file upload

                    $.ajax({
                        url: $(form).attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            $('button[type="submit"]').prop('disabled', true).html(
                                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...'
                            );
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href =
                                        "{{ url('/pelapor/laporan_saya') }}";
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            var response = xhr.responseJSON;
                            var message = "Terjadi kesalahan pada server";

                            if (response && response.message) {
                                message = response.message;
                            } else if (xhr.status === 422) {
                                message = "Validasi gagal: ";
                                var errors = response.errors;
                                $.each(errors, function(field, messages) {
                                    message += messages.join(', ') + " ";
                                });
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message
                            });
                        },
                        complete: function() {
                            $('button[type="submit"]').prop('disabled', false).html(
                                'Kirim Laporan');
                        }
                    });
                }
            });
        });
    </script>
@endpush
