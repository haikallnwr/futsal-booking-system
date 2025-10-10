@extends('auth.layouts.main')
@section('container')
    @auth
        <div class="container mt-5">
            <div class="main-body mt-5">
                <div class="row mt-5">
                    <div class="col-md-4 mb-5">
                        <div class="card mt-5">
                            <div class="card-body my-4">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('assets/img/default-avatar.png') }}" alt="User Photo"
                                        class="img-fluid rounded-circle shadow-sm mb-3"
                                             style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff;">
                                    <div class="mt-3">
                                        <h4>{{ auth()->user()->fullname }}</h4>
                                        <p class="text-secondary mb-1">{{ auth()->user()->username }}</p>
                                        <p class="text-muted font-size-sm">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 mt-5">
                        <div class="card mb-3 mt-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Full Name</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ auth()->user()->fullname }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Username</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ '@' . auth()->user()->username }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Email</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ auth()->user()->email }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Phone</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ auth()->user()->notelp }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Address</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{ auth()->user()->alamat }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a href="{{ route('profile.edit') }}" class="btn btn-primary ">Edit Profile</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth
@endsection

