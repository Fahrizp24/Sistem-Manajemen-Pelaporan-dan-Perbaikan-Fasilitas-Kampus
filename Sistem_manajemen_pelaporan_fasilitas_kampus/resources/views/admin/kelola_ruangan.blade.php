@extends('layouts.template')

@section('content')
<div class="page-content">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Ruangan</h5>
            <button type="button" class="btn btn-success mb-3" onclick="modalAction('{{ route('admin.create_ruangan') }}')">
                + Tambah Ruangan
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="tableRuangan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Ruangan</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalRuangan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-body">
            <div id="contentModalRuangan">Memuat...</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function modalAction(url) {
    $.ajax({
        url: url,
        type: "GET",
        success: function (res) {
            $('#contentModalRuangan').html(res);
            $('#modalRuangan').modal('show');
        },
        error: function () {
            $('#contentModalRuangan').html('<p class="text-danger">Gagal memuat data.</p>');
        }
    });
}

$(document).on('click', '.btnEditruangan', function () {
    var id = $(this).data('id');
    modalAction('/admin/edit_ruangan/' + id);
});

$('#tableRuangan').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route("admin.data_ruangan") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        }
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'ruangan_nama', name: 'ruangan_nama' },
        { data: 'ruangan_deskripsi', name: 'ruangan_deskripsi' },
        { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
    ]
});

</script>
@endpush
