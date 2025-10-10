@extends('auth.layouts.main')
@section('container')
        <div id="auth">
            <div class="row justify-content-center">
                <div id="auth-left" class="col-md-8">
                    <div class="card shadow-sm mt-3 py-3 rounded-4 px-4">
                        <h1 class="mt-2 fs-1 text-success">Sign Up.</h1>
                        <p class="mb-3 fs-5 text-gray-600">
                            Input your data to register to our website.
                        </p>
                        <form action="/register" method="post">
                            @csrf
                            <div class="form-group position-relative has-icon-left mb-2">
                                <input type="text" name="fullname" id="fullname" value="{{ old('fullname') }}"
                                    class="form-control form-control-xl @error('fullname') is-invalid @enderror"
                                    placeholder="Full Name" required>
                                <div class="form-control-icon">
                                    <i class="bi bi-person"></i>
                                </div>
                                @error('fullname')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group position-relative has-icon-left mb-2">
                                <input type="text" name="username" id="username" value="{{ old('username') }}"
                                    class="form-control form-control-xl @error('username') is-invalid @enderror"
                                    placeholder="Username" required>
                                <div class="form-control-icon">
                                    <i class="bi bi-person"></i>
                                </div>
                                @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group position-relative has-icon-left mb-2">
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="form-control form-control-xl @error('email') is-invalid @enderror"
                                    placeholder="Email" required>
                                <div class="form-control-icon">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group position-relative has-icon-left mb-2">
                                <input type="tel" name="notelp" id="notelp" value="{{ old('notelp') }}"
                                    class="form-control form-control-xl @error('notelp') is-invalid @enderror" placeholder="+62"
                                    required>
                                <div class="form-control-icon">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                @error('notelp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group position-relative has-icon-left mb-2">
                                <textarea class="form-control form-control-xl @error('alamat') is-invalid @enderror" placeholder="Address"
                                    name="alamat" id="alamat" style="resize: none" rows="3">{{ old('alamat') }}</textarea>
                                <div class="form-control-icon">
                                    <i class="bi bi-house-door"></i>
                                </div>
                                @error('alamat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group position-relative has-icon-left mb-2">
                                <input type="password" name="password" id="password"
                                    class="form-control form-control-xl @error('password') is-invalid @enderror"
                                    placeholder="Password" required>
                                <div class="form-control-icon">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button class="btn btn-primary btn-block btn-lg shadow-sm mt-3">
                                Sign Up
                            </button>
                        </form>
                        <div class="text-center mt-3">
                            <p class="text-gray-600 fs-5">
                                Already have an account?
                                <a href="/login" class="font-bold">Log in</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
