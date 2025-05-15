@extends('layouts.template')
@section('content')
<div class="row">
    <!-- [ Profile ] start -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <div class="user-profile">
                        <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="User Image" class="img-fluid rounded-circle" width="150">
                    </div>
                    <h4 class="mt-3 mb-0">{{ Auth::user()->name }}</h4>
                    <p class="text-muted">{{ Auth::user()->role->name ?? 'User' }}</p>
                    
                    <div class="d-flex justify-content-center mt-4">
                        <a href="#" class="btn btn-primary btn-sm me-2">Edit Photo</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Change Password</a>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="user-details">
                    <h5>About</h5>
                    <p class="text-muted mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur convallis egestas aliquet.</p>
                    
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="ti ti-mail me-2"></i> {{ Auth::user()->email }}
                        </li>
                        <li class="mb-2">
                            <i class="ti ti-phone me-2"></i> +1 234 567 890
                        </li>
                        <li class="mb-2">
                            <i class="ti ti-map-pin me-2"></i> New York, USA
                        </li>
                        <li class="mb-2">
                            <i class="ti ti-calendar me-2"></i> Joined {{ Auth::user()->created_at->format('M Y') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5>Edit Profile</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Full Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                            <small class="text-muted">Contact admin to change email</small>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Phone</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Address</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="address" rows="3">{{ old('address', Auth::user()->address) }}</textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Bio</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="bio" rows="3">{{ old('bio', Auth::user()->bio) }}</textarea>
                            <small class="text-muted">A short bio about yourself</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-primary me-2">Update Profile</button>
                            <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>Change Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Current Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="current_password" required>
                            @error('current_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">New Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="new_password" required>
                            @error('new_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Confirm Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="new_password_confirmation" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-primary me-2">Change Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- [ Profile ] end -->
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
    // Script khusus untuk halaman profile
    document.addEventListener('DOMContentLoaded', function() {
        // Tambahkan script khusus di sini jika diperlukan
        console.log('Profile page loaded');
    });
</script>
@endpush