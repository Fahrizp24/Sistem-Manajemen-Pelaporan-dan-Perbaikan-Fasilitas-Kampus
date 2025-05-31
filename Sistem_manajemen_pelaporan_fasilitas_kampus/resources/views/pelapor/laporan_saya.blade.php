@extends('layouts.template')

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
                                <th>Fasilitas</th>
                                <th>Tanggal Laporan</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporan_saya as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->fasilitas->gedung->nama }}</td>
                                    <td>{{ $item->fasilitas->nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y') }}</td>
                                    <td>{{ Str::limit($item->deskripsi, 50) }}</td>
                                    <td>
                                        @php
                                            $badgeClass =
                                                [
                                                    'diajukan' => 'bg-secondary',
                                                    'diterima' => 'bg-info',
                                                    'tidak diterima' => 'bg-danger',
                                                    'konfirmasi' => 'bg-primary',
                                                    'memilih teknisi' => 'bg-info',
                                                    'diperbaiki' => 'bg-warning',
                                                    'telah diperbaiki' => 'bg-warning',
                                                    'revisi' => 'bg-warning',
                                                    'selesai' => 'bg-success',
                                                ][$item->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="divider badge {{ $badgeClass }}">{{ ucfirst($item->status) }}</span>
                                    </td>
                                    <td>
                                        <button
                                            onclick="showDetailModal('{{ url('pelapor/laporan_saya/' . $item->laporan_id) }}')"
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Structure -->
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Laporan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalContent">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <!-- Ensure jQuery is loaded before Bootstrap -->
    <script src="{{ asset('mazer/dist/assets/extensions/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap Bundle with Popper (includes modal functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('mazer/dist/assets/static/js/initTheme.js') }}"></script>
    <script src="{{ asset('mazer/dist/assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('mazer/dist/assets/extensions/rater-js/index.js?v=2') }}"></script>
    <script src="{{ asset('mazer/dist/assets/static/js/pages/rater-js.js') }}"></script>

    <script>
        // Fungsi untuk menginisialisasi rating
        function initRating() {
            document.querySelectorAll('.my-rating:not([data-initialized])').forEach(el => {
                const isReadOnly = el.dataset.readonly === 'true';
                const laporanId = el.dataset.laporanId;

                const rater = raterJs({
                    element: el,
                    starSize: 32,
                    readOnly: isReadOnly,
                    rating: parseFloat(el.dataset.rating) || 0,
                    starHighlight: true,
                    onRate: function(rating) {
                        if (!isReadOnly) {
                            const form = document.getElementById(`ratingForm_${laporanId}`);
                            if (form) {
                                form.querySelector('.rating-input').value = rating;
                                form.querySelector('.submit-rating').disabled = false;

                                // Auto submit setelah rating dipilih
                                form.submit();
                            }
                        }
                    }
                });

                el.setAttribute('data-initialized', 'true');
                el.rater = rater; // Simpan instance rater
            });
        }

        // Event listener untuk modal
        document.getElementById('detailModal').addEventListener('shown.bs.modal', function() {
            initRating();
        });

        // Inisialisasi saat pertama kali load
        document.addEventListener('DOMContentLoaded', function() {
            initRating();
        });

        // Fungsi showDetailModal yang diperbarui
        function showDetailModal(url) {
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('modalContent').innerHTML = html;
                    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                    modal.show();

                    // Beri sedikit delay untuk memastikan modal sepenuhnya terbuka
                    setTimeout(initRating, 300);
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('modalContent').innerHTML = `
                <div class="alert alert-danger">
                    Gagal memuat konten. Silakan coba lagi.
                </div>
            `;
                    new bootstrap.Modal(document.getElementById('detailModal')).show();
                });
        }
    </script>
@endpush
{{-- @css
    .rater-base {
    display: inline-block;
    position: relative;
    z-index: 10;
    }

    .rater-star {
    cursor: pointer;
    color: #ffd700; /* Warna bintang */
    font-size: 32px;
    transition: all 0.2s;
    }

    .rater-star:hover {
    transform: scale(1.2);
    }

    .rater-star.will-be-active {
    color: #ffdf00;
    }

    .rater-star.active {
    color: #ffd700;
    }

    .rater-readonly .rater-star {
    cursor: default;
    }
@endcss --}}
