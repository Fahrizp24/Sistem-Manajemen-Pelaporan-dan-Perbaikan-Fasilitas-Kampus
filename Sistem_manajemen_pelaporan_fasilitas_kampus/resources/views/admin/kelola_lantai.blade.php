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
            <h5 class="mb-0">Daftar Lantai</h5>
            <button type="button" class="btn btn-success mb-3" onclick="modalAction('{{ route('admin.create_lantai') }}')">
                + Tambah Lantai
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="tableLantai">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lantai</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalLantai" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-body">
            <div id="contentModalLantai">Memuat...</div>
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
            $('#contentModalLantai').html(res);
            $('#modalLantai').modal('show');
        },
        error: function () {
            $('#contentModalLantai').html('<p class="text-danger">Gagal memuat data.</p>');
        }
    });
}

$(document).on('click', '.btnEditlantai', function () {
    var id = $(this).data('id');
    modalAction('/admin/edit_lantai/' + id);
});

$$('#tableLantai').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route("admin.data_lantai") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        }
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
        { data: 'lantai_nama', name: 'lantai_nama' },
        { data: 'lantai_deskripsi', name: 'lantai_deskripsi' },
        { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
    ]
});

</script>
@endpush
