@extends('auth.layouts.main')
@section('container')
        <section id="contact" class="pt-5" style="margin-top: 100px;">
            <div class="container">
                <div class="row align-items-center g-5">
                    <!-- Kiri: Informasi Kontak -->
                    <div class="col-lg-5 text-black">
                        <h2 class="fw-bold mb-3 text-success">Hubungi Kami</h2>
                        <p class="mb-4">Apakah Anda memiliki keluhan? atau ingin bekerja sama dengan kami, namun bingung
                            bagaimana cara menghubunginya?</p>

                        <p class="mb-2">
                            <i class="bi bi-envelope-fill me-2 text-success"></i> afuta@gmail.com
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-telephone-fill me-2 text-success"></i> Support: (+62) 812 3456 7890
                        </p>
                    </div>

                    <!-- Kanan: Formulir -->
                    <div class="col-lg-7">
                        <div class="bg-white rounded-4 shadow-sm p-4 p-md-5">

                            <h5 class="fw-bold mb-4">Silahkan hubungi jika ada keperluan,<br>dengan senang hati kami
                                mendengarnya!</h5>

                            @if (session('success'))
                                <div id="success" class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <strong>{{ session('success') }}</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (session('failed'))
                                <div id="failed" class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <strong>{{ session('failed') }}</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif


                            <form action="/contact" method="POST" id="form-contact">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nama</label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror" id="name"
                                            placeholder="Masukkan Nama Anda" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror" id="email"
                                            placeholder="Masukkan Email Anda" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subjek</label>
                                    <input type="text" name="subject" id="subject"
                                        class="form-control @error('subject') is-invalid @enderror" placeholder="Subjek"
                                        value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Deskripsi Pesan</label>
                                    <input id="message" type="hidden" name="message" value="{{ old('message') }}"
                                        required>
                                    <trix-editor input="message"
                                        class="@error('message') is-invalid @enderror"></trix-editor>
                                    @error('message')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid">
                                    <button type="submit" id="submit" class="btn btn-primary btn-lg">
                                        <span class="spinner-border spinner-border-sm d-none me-2" id="loading"
                                            role="status"></span>
                                        Kirim Pesan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection
