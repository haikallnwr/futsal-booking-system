@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        <h3>User Details: {{ $user->fullname }}</h3>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Full Name:</strong> {{ $user->fullname }}</p>
                            <p><strong>Username:</strong> {{ $user->username }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Phone:</strong> {{ $user->notelp }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Address:</strong> {{ $user->alamat }}</p>
                            <p><strong>Role:</strong> {{ $user->role->role ?? 'N/A' }}</p>
                            <p><strong>Joined At:</strong> {{ $user->created_at->format('d M Y, H:i') }}</p>
                            <p><strong>Last Updated:</strong> {{ $user->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <hr>
                    <a href="{{ route('dev.users.edit', $user->id) }}" class="btn btn-warning">Edit User</a>
                    <a href="{{ route('dev.users.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </section>
        {{-- Anda bisa menambahkan bagian untuk menampilkan order user, dll. di sini --}}
    </div>
@endsection