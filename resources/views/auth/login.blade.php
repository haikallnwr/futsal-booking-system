@extends('auth.layouts.main')
@section('container')
        <div id="auth">
            <div class="row justify-content-center mt-5">
                <div id="auth-left " class="col-md-5 mt-5">
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session()->has('loginError'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('loginError') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card shadow-sm mt-3 py-3 rounded-4 px-4">
                    <h1 class="mt-2 fs-1 text-success">Log in.</h1>
                    <p class="mb-3 fs-5 text-gray-600">
                        Log in with your data that you entered during registration.
                    </p>
                    <form action="/login" method="post">
                        @csrf
                        <div class="form-group position-relative has-icon-left mb-2">
                            <input type="text" name="username" id="username"
                                class="form-control form-control-xl @error('username') is-invalid @enderror"
                                placeholder="Username" required autofocus>
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-2">
                            <input type="password" name="password" id="password"
                                class="form-control form-control-xl @error('password') is-invalid @enderror"
                                placeholder="Password" required>
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-sm mt-3">
                            Log in
                        </button>
                    </form>
                    <div class="text-center mt-3">
                        <p class="text-gray-600 fs-5">
                            Don't have an account?
                            <a href="/register" class="font-bold">Sign up</a>.
                        </p>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
