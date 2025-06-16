@if (empty($laporan))
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/pelapor/laporan_saya') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div class="card-body">
            <!-- Kolom Kanan untuk Informasi Laporan -->
            <div class="col-12">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th width="30%">Pelapor</th>
                        <td>{{ $laporan->pelapor->nama }}</td>
                    </tr>
                    <tr>
                        <th>Gedung</th>
                        <td>{{ $laporan->fasilitas->ruangan->lantai->gedung->gedung_nama }}</td>
                    </tr>
                    <tr>
                        <th>Lantai</th>
                        <td>{{ $laporan->fasilitas->ruangan->lantai->lantai_nama }}</td>
                    </tr>
                    <tr>
                        <th>Ruangan</th>
                        <td>{{ $laporan->fasilitas->ruangan->ruangan_nama }}</td>
                    </tr>
                    <tr>
                        <th>Fasilitas</th>
                        <td>{{ $laporan->fasilitas->fasilitas_nama }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $laporan->status }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Laporan</th>
                        <td>{{ $laporan->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Ditugaskan Oleh</th>
                        <td>{{ $laporan->sarpras->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Ditugaskan Kepada</th>
                        <td>{{ $laporan->teknisi->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $laporan->deskripsi }}</td>
                    </tr>
                </table>
            </div>
        </div>
<div class="row mt-4">
        <div class="col-12">
            @if ($laporan->foto)
                <img src="{{ Storage::url('foto_laporan/foto_diterima_11.jpg' ) }}" 
                     class="img-thumbnail w-150% mx-auto d-block"
                     style="max-width: 100%; height: 100%; max-height: 400px;">
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-image"></i> Tidak ada foto
                </div>
            @endif
        </div>
    </div>
         @if ($laporan->status == 'selesai')
        <div class="mt-4">
            <h5>Rating Layanan</h5>
            @if ($laporan->rating !== null)
                <div class="my-rating" 
                     data-rating="{{ $laporan->rating }}" 
                     data-readonly="true"
                     data-laporan-id="{{ $laporan->laporan_id }}"></div>
                <p class="text-muted mt-2">Anda telah memberikan rating {{ $laporan->rating }} bintang</p>
            @else
                <div class="my-rating" 
                     data-rating="0" 
                     data-readonly="false"
                     data-laporan-id="{{ $laporan->laporan_id }}"></div>
                <form id="ratingForm_{{ $laporan->laporan_id }}" class="rating-form" method="POST" 
                      action="{{ url('pelapor/laporan_saya/rating/', $laporan->laporan_id) }}">
                    @csrf
                    <input type="hidden" name="rating" id="ratingInput" class="rating-input">
                    <button type="submit" id="submitRating" class="btn btn-primary mt-2 submit-rating" disabled>
                        Simpan Rating
                    </button>
                </form>
            @endif
        </div>
    @endif
@endif

@push('scripts')
<script src="{{ asset('mazer/dist/assets/extensions/rater-js/index.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inisialisasi rating
        const ratingElements = document.querySelectorAll(".my-rating");
        
        ratingElements.forEach(element => {
            const isReadOnly = element.dataset.readonly === 'true';
            const currentRating = parseFloat(element.dataset.rating) || 0;
            const laporanId = element.dataset.laporanId;
            const ratingInput = document.querySelector(`#ratingForm_${laporanId} .rating-input`);
            const submitButton = document.querySelector(`#ratingForm_${laporanId} .submit-rating`);
            
            const rater = raterJs({
                element: element,
                starSize: 32,
                readOnly: isReadOnly,
                rating: currentRating,
                onRate: function(rating) {
                    if (!isReadOnly) {
                        // Update hidden input value
                        ratingInput.value = rating;
                        
                        // Enable submit button
                        submitButton.disabled = false;
                    }
                }
            });
            
            // Simpan instance rater ke element untuk referensi
            element.rater = rater;
        });
    });
</script>
@endpush
