    @extends('auth.layouts.main')

    @section('container')
        <section id="features" class="features mt-5">
            <div class="container aos-init aos-animate mt-5" data-aos="fade-up">
                <div class="card">
                    @if($gor->images->count() > 1)
                        <!-- Carousel untuk multiple images -->
                        <div id="gorImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($gor->images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img class="d-block w-100 rounded-4" 
                                            src="{{ asset('storage/' . $image->image_path) }}" 
                                            alt="Gambar {{ $gor->nama_gor }} - {{ $index + 1 }}"
                                            style="height: 500px; object-fit: cover; object-position: center;">
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Previous Button -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#gorImagesCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            
                            <!-- Next Button -->
                            <button class="carousel-control-next" type="button" data-bs-target="#gorImagesCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            
                            <!-- Indicators (dots) -->
                            <div class="carousel-indicators">
                                @foreach($gor->images as $index => $image)
                                    <button type="button" 
                                            data-bs-target="#gorImagesCarousel" 
                                            data-bs-slide-to="{{ $index }}" 
                                            class="{{ $index === 0 ? 'active' : '' }}"
                                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                            aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- Single image jika hanya ada satu gambar -->
                        <img class="rounded-4" 
                            src="{{ asset('storage/' . $gor->images->first()->image_path) }}" 
                            alt="{{ $gor->nama_gor }}"
                            style="height: 500px; width: 100%; object-fit: cover; object-position: center;">
                    @endif
                </div>
                <div class="row">
                <div class="col-md-8">
                    <div class="d-flex align-items-bottom gap-3">
                        <div><i class='bx  bxs-football-pitch bx-md ' style="color: #198754" ></i></div>
                        <h1 class="fs-2">{{ $gor->nama_gor }}</h1>
                    </div>
                    <div class="d-flex align-items-bottom gap-3 mt-2">
                        <div class="ms-1"><i class='bx  bxs-location-alt pt-1'  style='color:#e08318'></i></div> 
                        <p>Kota {{ $gor->wilayah }}, Daerah Khusus Ibukota Jakarta</p>
                    </div>
                    <hr class="mt-0 " style="width: 95%">
                    <div>
                        <h2 class="fs-5">Deskripsi</h2>
                        <p>{!! $gor->deskripsi !!}</p>
                    </div>
                    <div>
                        <h2 class="fs-5">Fasilitas & Informasi Lainnya</h2>
                        <p>{!! $gor->fasilitas !!}</p>
                    </div>
                    <div class="card shadow-sm mb-4 border border-dark border-opacity-75 bg-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title fw-bold">Lokasi</h5>
                                    <p class="card-text text-muted mb-1">{{ $gor->alamat_gor }}</p>
                                    <p class="card-text text-muted mb-0">
                                        @if($gor->kecamatan)
                                            Kec. {{ $gor->kecamatan }},
                                        @endif
                                        @if($gor->wilayah)
                                            {{ $gor->wilayah }}
                                        @endif
                                    </p>
                                </div>

                                {{-- Bagian Kanan: Tombol Buka Peta --}}
                                {{-- Tombol hanya akan muncul jika GOR memiliki data latitude dan longitude --}}
                                @if($gor->latitude && $gor->longitude)
                                    <div class="text-center border-start border-1 border-success ps-4">
                                        <a href="https://www.google.com/maps?q={{ $gor->latitude }},{{ $gor->longitude }}" 
                                        target="_blank" 
                                        class="text-decoration-none">
                                            <div class="map-icon-container">
                                                <i class="bi bi-geo-alt-fill"></i>
                                            </div>
                                            <span class="fw-bold text-success d-block mt-1">Buka Peta</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    

                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border border-dark border-opacity-75 p-4">
                        {{-- Cek dulu apakah GOR ini punya lapangan atau tidak --}}
                        @if($gor->field->isNotEmpty())

                            {{-- Ambil harga termurah dari semua lapangan --}}
                            @php
                                $hargaTermurah = $gor->field->min('harga_sewa');
                            @endphp

                            <p>Mulai dari</p>
                            <div class="d-flex align-items-baseline"> {{-- align-items-baseline agar sejajar rapi --}}
                                <h1 class="fs-3 fw-bold">Rp {{ number_format($hargaTermurah, 0, ',', '.') }}</h1>
                                <p class="ms-2 text-muted">Per Jam</p>
                            </div>
                            <a href="#booking-form" class="btn btn-primary mt-2 rounded-3">Booking Sekarang</a>

                        @else
                            {{-- Tampilkan ini jika GOR belum punya lapangan --}}
                            <p class="fw-bold">Harga Belum Tersedia</p>
                            <p class="text-muted small">Silakan hubungi kontak untuk informasi lebih lanjut.</p>
                            <a href="#" class="btn btn-secondary mt-2 rounded-2 disabled">Booking Belum Tersedia</a>

                        @endif
                    </div>
                    <div class="card shadow-sm border border-dark border-opacity-75 p-4 mt-0">
                        <p class="fw-bold mb-1">Kontak</p>
                        <hr>
                        
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <a href="https://wa.me/{{ $gor->whatsapp }}" class="text-success">
                                <i class="bi bi-whatsapp fs-5"></i>
                            </a>
                            <span class="">{{ $gor->whatsapp }}</span>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3">
                            <a href="https://instagram.com/{{ $gor->instagram }}" class="text-danger">
                                <i class="bi bi-instagram fs-5"></i>
                            </a>
                            <span>{{ $gor->instagram }}</span>
                        </div>
                    </div>

                </div>
                </div>

                {{--<div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Kontak & Media Sosial</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @if($gor->whatsapp)
                            <li class="list-group-item">
                                <a href="https://wa.me/{{ $gor->whatsapp }}" target="_blank" class="text-decoration-none d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-whatsapp me-2 text-success"></i>WhatsApp</span>
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </li>
                            @endif
                            @if($gor->instagram)
                            <li class="list-group-item">
                                <a href="https://instagram.com/{{ $gor->instagram }}" target="_blank" class="text-decoration-none d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-instagram me-2 text-danger"></i>Instagram</span>
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </li>
                            @endif
                            @if($gor->email)
                            <li class="list-group-item">
                                <a href="mailto:{{ $gor->email }}" class="text-decoration-none d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-envelope-fill me-2 text-primary"></i>Email</span>
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </li>
                            @endif
                        </ul>
                        @if(!$gor->whatsapp && !$gor->instagram && !$gor->email)
                            <p class="text-muted mb-0">Tidak ada informasi kontak yang tersedia.</p>
                        @endif
                    </div>
                </div>--}}
                <div class="row mt-4">
                    <div class="col">
                        <!-- Tabs Navigation -->
                        <ul class="nav nav-pills nav-fill mb-4" id="pills-tab" role="tablist">
                            @foreach ($gor->field as $index => $field)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                            id="pills-{{ $field->slug_lapangan }}-tab" 
                                            data-bs-toggle="pill" 
                                            data-bs-target="#pills-{{ $field->slug_lapangan }}" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="pills-{{ $field->slug_lapangan }}" 
                                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                        <i class="bi bi-dribbble me-2 p-0 mb-2 "></i>{{ $field->nama_lapangan }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Tabs Content -->
                        <div class="tab-content" id="pills-tabContent">
                            @foreach ($gor->field as $index => $field)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                                    id="pills-{{ $field->slug_lapangan }}" 
                                    role="tabpanel" 
                                    aria-labelledby="pills-{{ $field->slug_lapangan }}-tab">
                                    
                                    <!-- Field Info Card -->
                                    <div class="card shadow-sm border border-succes border-opacity-50 mb-4 rounded-4">
                                        <div class="row g-0">
                                            <div class="col-md-5">
                                                <img src="{{ asset(Str::startsWith($field->foto_lapangan, '/') ? $field->foto_lapangan : '/' . $field->foto_lapangan) }}"
                                                    class="img-fluid h-100 w-100 rounded-start-1"
                                                    alt="{{ $field->nama_lapangan }}"
                                                    style="object-fit: cover; min-height: 250px;">
                                            </div>
                                            <div class="col-md-7">
                                                <div class="card-body p-4">
                                                    <h4 class="card-title mb-4">{{ $field->nama_lapangan }}</h4>
                                                    
                                                    <div class="row mb-4">
                                                        <div class="col-6">
                                                            <div class="border-start border-4 border-success ps-3">
                                                                <small class="text-muted text-uppercase fw-semibold">Harga Sewa</small>
                                                                <h6 class=" mb-0 fs-5">
                                                                    Rp {{ number_format($field->harga_sewa, 0, ',', '.') }}
                                                                    <small class="fs-6 text-muted">/jam</small>
                                                                </h6>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="border-start border-4 border-success ps-3">
                                                                <small class="text-muted text-uppercase fw-semibold">Jenis Lapangan</small>
                                                                <h6 class=" mb-0">{{ $field->keterangan_lapangan }}</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-success-subtle text-success me-2 mt-1">
                                                            <i class="bi bi-check-circle me-1"></i>Tersedia
                                                        </span>
                                                        <small class="text-muted">Lapangan siap digunakan</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Schedule Card -->
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-gradient bg-primary text-white">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar-event me-3 mb-3"></i>
                                                <h5 class="mb-0">Jadwal Terisi</h5>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            @if ($field->schedule->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="border-0 fw-semibold">No</th>
                                                                <th class="border-0 fw-semibold">
                                                                    <i class="bi bi-person me-1"></i>Nama
                                                                </th>
                                                                <th class="border-0 fw-semibold">
                                                                    <i class="bi bi-calendar me-1"></i>Tanggal
                                                                </th>
                                                                <th class="border-0 fw-semibold">
                                                                    <i class="bi bi-clock me-1"></i>Waktu
                                                                </th>
                                                                <th class="border-0 fw-semibold">
                                                                    <i class="bi bi-hourglass me-1"></i>Durasi
                                                                </th>
                                                                <th class="border-0 fw-semibold">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($field->schedule as $jadwal)
                                                                <tr>
                                                                    <td class="align-middle">
                                                                        <span class="badge bg-dark text-black rounded-circle">{{ $loop->iteration }}</span>
                                                                    </td>
                                                                    <td class="align-middle fw-medium">{{ $jadwal->user->fullname ?? '-' }}</td>
                                                                    <td class="align-middle">{{ \Carbon\Carbon::parse($jadwal->order->tanggal_main)->format('d M Y') }}</td>
                                                                    <td class="align-middle">
                                                                        <span class="badge bg-secondary-subtle text-secondary">
                                                                            {{ \Carbon\Carbon::parse($jadwal->order->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->order->jam_selesai)->format('H:i') }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="align-middle">{{ $jadwal->order->durasi ?? '-' }}</td>
                                                                    <td class="align-middle">
                                                                        <span class="badge bg-danger">{{ $jadwal->status ?? 'Terisi' }}</span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="text-center py-5">
                                                    <div class="mb-3">
                                                        <i class="bi bi-calendar-check display-1 text-success opacity-25"></i>
                                                    </div>
                                                    <h5 class="text-muted">Belum Ada Jadwal</h5>
                                                    <p class="text-muted mb-0">Lapangan ini masih kosong dan siap untuk dibooking!</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>    
        </section>
        <!-- Form Booking -->
        <section id="booking-form" class="py-5 bg-light ">
            {{-- Letakkan kode ini di bagian atas card-body --}}
            @if (session('error_booking'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Pemesanan Gagal!</strong> {{ session('error_booking') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif

            {{-- Notifikasi untuk error validasi bawaan Laravel --}}
            @if ($errors->any())
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Pemesanan Gagal!</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif
            <div class="container rounded shadow-sm bg-white p-4 rounded-4" data-aos="fade-up">
                <div class=" text-center mb-1">
                    <h1>Form Booking</h1>
                </div>
                <form class="needs-validation col-12" action="/orderStore" method="post">
                    @csrf
                    <div class="row g-3">
                        {{-- mt-5 --}}
                        <div class="col-lg-12">
                            <input type="hidden" name="gor_id" id="gor_id" value="{{ $gor->id }}">
                            <label for="field" class="form-label">Pilih Lapangan</label>
                            <select class="form-select  @error('field') is-invalid @enderror" id="field"
                                name="field" required>
                                <option value="">--Pilih Lapangan--</option>
                                @foreach ($gor->field as $field)
                                    <option value="{{ $field->id }}|{{ $field->harga_sewa }}">
                                        {{ $field->nama_lapangan }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                @error('field')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label for="country" class="form-label">Pilih Tanggal Main</label>
                            <div class="input-group date" id="datepicker">
                                <input type="text" class="form-control @error('tanggal_main') is-invalid @enderror"
                                    id="tanggal_main" placeholder="--Pilih Tanggal Main--" name="tanggal_main">
                                <span class="input-group-append">
                                </span>
                            </div>
                            <div class="invalid-feedback">
                                @error('tanggal_main')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label for="jam_mulai" class="form-label">Jam Mulai</label>
                            <select class="form-select @error('jam_mulai') is-invalid @enderror" id="jam_mulai"
                                name="jam_mulai" required>
                                <option value="">--Pilih Jam Mulai--</option>
                                <option value="09:00">09:00</option>
                                <option value="10:00">10:00</option>
                                <option value="11:00">11:00</option>
                                <option value="12:00">12:00</option>
                                <option value="13:00">13:00</option>
                                <option value="14:00">14:00</option>
                                <option value="15:00">15:00</option>
                                <option value="16:00">16:00</option>
                                <option value="17:00">17:00</option>
                                <option value="18:00">18:00</option>
                                <option value="19:00">19:00</option>
                                <option value="20:00">20:00</option>
                                <option value="21:00">21:00</option>
                                <option value="22:00">22:00</option>
                            </select>
                            @error('namaLapangan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-4">
                            <label for="durasi" class="form-label">Durasi</label>
                            <select class="form-select  @error('durasi') is-invalid @enderror" id="durasi"
                                name="durasi" required>
                                <option value="">--Pilih Durasi--</option>
                                <option value="1">1 Jam</option>
                                <option value="2">2 Jam</option>
                            </select>
                            @error('durasi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <hr class="my-4">
                        <div class="form-check">
                            <span class="text-success">Harga</span>
                            <h4 class="mb-3" id="harga">Rp 0</h4>
                        </div>
                        <hr class="my-4">
                        @auth
                            <button class="w-100 btn btn-success btn-lg rounded-3" type="submit" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop">Checkout
                            </button>
                        @else
                            <a href="{{ route('login.required') }}" class="w-100 btn btn-success btn-lg">Checkout</a>
                        @endauth
                    </div>
                </form>
            </div>
        </section>

        <script>
            let durasi = document.getElementById("durasi");
            let field = document.getElementById("field");
            let harga = document.getElementById("harga");

            durasi.addEventListener("change", hitung);
            field.addEventListener("change", hitung);

            function hitung() {
                let durasiValue = parseFloat(durasi.value);
                let fieldValue = field.value;
                let arr = fieldValue.split("|");
                let hargaSewa = parseFloat(arr[1]);
                let harga = hargaSewa * durasiValue;
                if (isNaN(harga)) {
                    document.getElementById("harga").innerHTML = "Rp 0";
                } else {
                    document.getElementById("harga").innerHTML = harga.toLocaleString(
                        'id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }
                    );
                }
            }
        </script>
    @endsection
