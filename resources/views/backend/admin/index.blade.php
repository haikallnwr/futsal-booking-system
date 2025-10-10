@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        {{-- Variabel $gor dan statistik lainnya dikirim dari DashboardAdminController@index --}}
        <h3>Dashboard: {{ $gor->nama_gor ?? 'My GOR' }}</h3>
        <p class="text-subtitle text-muted">Welcome, {{ Auth::user()->fullname }}!</p>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12">
                @include('backend.partials.alerts')
                <div class="row">
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldActivity"></i> {{-- Ganti ikon jika perlu --}}
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Fields</h6>
                                        <h6 class="font-extrabold mb-0">{{ $totalFields ?? 0 }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon blue mb-2">
                                            <i class="iconly-boldScan"></i> {{-- Ganti ikon jika perlu --}}
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Bookings Today</h6>
                                        <h6 class="font-extrabold mb-0">{{ $totalBookingsToday ?? 0 }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon red mb-2">
                                            <i class="iconly-boldDanger"></i> {{-- Ganti ikon jika perlu --}}
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Pending Payments</h6>
                                        <h6 class="font-extrabold mb-0">{{ $pendingPayments ?? 0 }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>GOR Information</h4>
                            </div>
                            <div class="card-body">
                                <p><strong>Address:</strong> {{ $gor->alamat_gor ?? 'N/A' }}</p>
                                <p><strong>Latitude:</strong> {{ $gor->latitude ?? '-' }}</p>
                                <p><strong>Longitude:</strong> {{ $gor->longitude ?? '-' }}</p>
                                <p><strong>Description:</strong> {{ $gor->deskripsi ?? 'No description provided.' }}</p>
                                <a href="{{ route('admin.gor.edit') }}" class="btn btn-primary mt-2">Edit My GOR Details</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                     <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Quick Links</h4>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('admin.fields.index') }}" class="btn btn-outline-success mb-2 d-block">Manage My Fields</a>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-info mb-2 d-block">View Bookings / Orders</a>
                                <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-warning d-block">View My Schedules</a>
                            </div>
                        </div>
                    </div>
                    {{-- Anda bisa menambahkan chart atau statistik lain di sini --}}
                </div>

            </div>
        </section>
    </div>
@endsection