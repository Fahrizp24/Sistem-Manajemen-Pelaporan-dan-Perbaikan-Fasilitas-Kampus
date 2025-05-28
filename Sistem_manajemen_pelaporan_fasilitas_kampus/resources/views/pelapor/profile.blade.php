@extends('layouts.template')
@section('content')
<div class="row min-vh-50 d-flex justify-content-center align-items-center">
    <div class="col-xl-4 col-lg-6 col-md-8">
        <div class="card mx-auto">
            <div class="card-body">
                <div class="text-center">
                    <div class="user-profile">
                        <img src="{{ asset('storage/foto_profil/'.$pelapor->foto_profil.'.jpg') }}" alt="User Image" class="img-fluid rounded-circle" width="150">
                    </div>
                    <h4 class="mt-3 mb-0">{{$pelapor->nama}}</h4>
                    <p class="text-muted">{{$pelapor->username}}</p>
                    
                    <div class="d-flex justify-content-center mt-4">
                        <a href="#" class="btn btn-primary btn-sm me-2">Edit Photo</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Change Password</a>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="user-details">
                    <h5>About</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="ti ti-mail me-2"></i> {{$pelapor->email}}
                        </li>
                        <li class="mb-2">
                            <i class="ti ti-phone me-2"></i> {{$pelapor->peran}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .user-profile {
        margin-bottom: 20px;
    }
    .user-profile img {
        border: 5px solid #f5f5f5;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .user-details h5 {
        margin-bottom: 15px;
        font-weight: 600;
    }
    .list-unstyled i {
        width: 20px;
        text-align: center;
    }
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Profile page loaded');
    });
</script>
@endpush
