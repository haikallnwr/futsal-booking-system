@extends('auth.layouts.main')

@section('container')
@auth
<div class="container-edit" data-aos="fade-up">
            <div class="row justify-content-center mx-0 p-0">
                <div class="col-lg-9 col-md-10"> {{-- Sedikit lebih lebar --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary">
                            <h4 class="mb-0 text-light">Edit Informasi Profil</h4>
                        </div>
                        <div class="card-body p-4 p-md-5">

                            {{-- Alert Messages --}}
                            {{-- Atau manual: --}}
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <h5 class="alert-heading">Oops! Terjadi Kesalahan:</h5>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            {{-- End Alert Messages --}}

                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT') {{-- Laravel menggunakan PUT untuk update resource --}}

                                <div class="row">
                                    {{-- Kolom Foto Profil --}}
                                    <div class="col-md-4 text-center mb-4">
                                        <img id="profileImagePreview"
                                             src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('assets/img/default-avatar.png') }}" {{-- Ganti default-avatar.png jika perlu --}}
                                             alt="Foto Profil {{ $user->fullname }}"
                                             class="img-fluid rounded-circle shadow-sm mb-3"
                                             style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff;">

                                        <label for="profile_photo" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-camera-fill"></i> Ganti Foto
                                        </label>
                                        <input type="file" class="form-control d-none @error('profile_photo') is-invalid @enderror" id="profile_photo" name="profile_photo" onchange="previewImage(event)">
                                        @error('profile_photo')
                                            <div class="invalid-feedback d-block text-center mt-2">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted d-block mt-1">Max: 2MB (JPG, PNG)</small>
                                    </div>

                                    {{-- Kolom Data Profil --}}
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="fullname" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('fullname') is-invalid @enderror" id="fullname" name="fullname" value="{{ old('fullname', $user->fullname) }}" required>
                                            @error('fullname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="notelp" class="form-label">Nomor Telepon</label>
                                            <input type="text" class="form-control @error('notelp') is-invalid @enderror" id="notelp" name="notelp" value="{{ old('notelp', $user->notelp) }}">
                                            @error('notelp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <hr class="my-4">
                                        <p class="text-muted small mb-2"><strong>Ganti Password</strong> (Kosongkan jika tidak ingin mengubah)</p>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password Baru</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Perubahan</button>
                                    <a href="{{ route('profile') }}" class="btn btn-outline-secondary rounded-3 ms-2 px-4">Kembali ke Dashboard</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth
@endsection

@push('js_scripts') {{-- Atau @section('js') jika layout utama memakai @yield('js') --}}
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('profileImagePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush