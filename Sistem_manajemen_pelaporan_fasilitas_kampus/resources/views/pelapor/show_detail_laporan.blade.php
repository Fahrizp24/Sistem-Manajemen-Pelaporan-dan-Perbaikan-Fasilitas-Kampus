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
        <table class="table table-bordered table-striped table-hover table-sm">
            <tr>
                <th>Pelapor</th>
                <td>{{ $laporan->pelapor->nama }}</td>
            </tr>
            <tr>
                <th>Gedung</th>
                <td>{{ $laporan->fasilitas->gedung->nama }}</td>
            </tr>
            <tr>
                <th>Fasilitas</th>
                <td>{{ $laporan->fasilitas->nama }}</td>
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
                <th>Foto</th>
                <td>
                    @if ($laporan->foto)
                        <div style="max-width: 200px; max-height: 200px; overflow: hidden;">
                            <img src="{{ Storage::url('foto_laporan/' . $laporan->foto) }}" class="img-fluid"
                                style="width: 100%; height: auto; object-fit: cover;">
                        </div>
                    @else
                        <span class="text-muted">Tidak ada foto</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{{ $laporan->deskripsi }}</td>
            </tr>
        </table>

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
                        action="{{  url('pelapor/laporan_saya/rating/', $laporan->laporan_id) }}">
                    @csrf
                    <input type="hidden" name="rating" class="rating-input">
                    <button type="submit" class="btn btn-primary mt-2 submit-rating" disabled>
                        Simpan Rating
                    </button>
                </form>
            @endif
        </div>
    @endif
    </div>
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
            
            const rater = raterJs({
                element: element,
                starSize: 32,
                readOnly: isReadOnly,
                rating: currentRating,
                onRate: function(rating) {
                    if (!isReadOnly) {
                        // Aktifkan tombol submit jika rating diberikan
                        document.getElementById('ratingInput').value = rating;
                        document.getElementById('submitRating').disabled = false;
                    }
                }
            });
            
            // Simpan instance rater ke element untuk referensi
            element.rater = rater;
        });
    });
</script>
@endpush
