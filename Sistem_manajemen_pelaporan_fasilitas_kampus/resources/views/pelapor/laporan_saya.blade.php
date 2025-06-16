@extends('layouts.template')
@section('title', 'Dashboard')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Laporan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table dataTable no-footer" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gedung</th>
                                <th>Lantai</th>
                                <th>Ruangan</th>
                                <th>Fasilitas</th>
                                <th>Tanggal Laporan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Structure -->
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="detailModalLabel">Detail Laporan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalContent">
                        <!-- Konten akan dimuat di sini -->
                        <div class="text-center py-4" id="loadingIndicator">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p>Memuat data laporan...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('mazer/dist/assets/extensions/rater-js/index.js?v=2') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('mazer/dist/assets/static/js/initTheme.js') }}"></script>
    <script src="{{ asset('mazer/dist/assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('mazer/dist/assets/extensions/rater-js/index.js?v=2') }}"></script>
    <script>
        function initRating() {
            document.querySelectorAll('.my-rating:not([data-initialized])').forEach(el => {
                const isReadOnly = el.dataset.readonly === 'true';
                const laporanId = el.dataset.laporanId;
                const currentRating = parseFloat(el.dataset.rating) || 0;

                const rater = raterJs({
                    element: el,
                    starSize: 32,
                    readOnly: isReadOnly,
                    rating: currentRating,
                    rateCallback: function (rating, done) {
                        this.setRating(rating);
                        done();

                        if (!isReadOnly) {
                            const form = document.getElementById(`ratingForm_${laporanId}`);
                            if (form) {
                                const input = form.querySelector('.rating-input');
                                const submitBtn = form.querySelector('.submit-rating');
                                if (input) input.value = rating;
                                if (submitBtn) submitBtn.disabled = false;
                            }
                        }
                    }
                });

                el.setAttribute('data-initialized', 'true');
                el.rater = rater;
            });
        }

        $('#table1').DataTable().clear().destroy();

        $(document).ready(function () {
            // Inisialisasi DataTables
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pelapor.laporan_saya.data') }}",
                    type: 'GET'
                },
                dom: '<"top"<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>><"table-responsive"t><"bottom"<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>>',

                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'gedung', name: 'fasilitas.ruangan.lantai.gedung.gedung_nama' },
                    { data: 'lantai', name: 'fasilitas.ruangan.lantai.lantai_nama' },
                    { data: 'ruangan', name: 'fasilitas.ruangan.ruangan_nama' },
                    { data: 'fasilitas', name: 'fasilitas.fasilitas_nama' },
                    { data: 'tanggal', name: 'tanggal_laporan' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });

        // Initialize rating when modal is shown
        document.getElementById('detailModal').addEventListener('shown.bs.modal', function () {
            initRating();
        });

        // Initialize any ratings on page load
        document.addEventListener('DOMContentLoaded', function () {
            initRating();
        });
        // Biarkan bagian lain seperti ini
        document.getElementById('detailModal').addEventListener('shown.bs.modal', function () {
            initRating();
        });

        document.addEventListener('DOMContentLoaded', function () {
            initRating();
        });

        function showDetailModal(url) {
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('modalContent').innerHTML = html;
                    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                    modal.show();

                    // Re-initialize rating setelah modal ditampilkan
                    setTimeout(() => {
                        initRating();

                        // Handle form submission
                        $('.rating-form').off('submit').on('submit', function (e) {
                            e.preventDefault();
                            handleRatingSubmit($(this));
                        });
                    }, 300);
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('modalContent').innerHTML = `
                    <div class="alert alert-danger">Gagal memuat konten. Silakan coba lagi.</div>
                `;
                    new bootstrap.Modal(document.getElementById('detailModal')).show();
                });
        }

        function handleRatingSubmit(form) {
            const submitBtn = form.find('.submit-rating');
            const modal = $('#detailModal');

            submitBtn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Menyimpan...
        `);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        modal.modal('hide');
                        $('#table1').DataTable().ajax.reload(null, false);
                    }
                },
                error: function (xhr) {
                    submitBtn.prop('disabled', false).html('Simpan Rating');

                    let errorMessage = 'Terjadi kesalahan saat menyimpan rating';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: errorMessage
                    });
                }
            });
        }
    </script>
@endpush
<style>
    .rater-star.will-be-active,
    .rater-star.active {
        color: #ffd700 !important;
    }

    .rater-star {
        color: #ddd;
        font-size: 32px;
        cursor: pointer;
        transition: color 0.2s;
    }

    .rater-star.will-be-active,
    .rater-star.active {
        color: #ffd700 !important;
    }

    .rater-star.active~.rater-star {
        color: #ddd !important;
    }
</style>