@extends('auth.layouts.main')
@section('container')

<section id="gor" class="gor mt-3">
            <div class="container pt-5" data-aos="fade-up">
                <div class="row mb-4">
                    <div class="col ml-auto">
                        <form action="{{ route('search') }}" method="GET" 
                          class="search-container">
                        <div class="row g-3 align-items-center px-3">
                            
                            <!-- Wilayah Selection -->
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent ">
                                        <i class="bx bxs-location-alt-2 text-success bx-sm"></i>
                                    </span>
                                    <select class="form-select" 
                                            id="search-wilayah" 
                                            name="wilayah">
                                        <option class="rounded-4" value="" disabled selected hidden class="text-dark">Pilih Wilayah</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Kecamatan Selection -->
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent">
                                        <i class="bx bxs-location-pin text-success bx-sm"></i>
                                    </span>
                                    <select class="form-select" 
                                            id="search-kecamatan" 
                                            name="kecamatan" 
                                            >
                                        <option value="" disabled selected hidden class="text-dark">Pilih Kecamatan</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Jenis Lapangan Selection -->
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent">
                                        <i class="bx bxs-football-pitch text-success bx-sm"></i>
                                    </span>
                                    <select class="form-select" 
                                            id="search-jenis-lapangan" 
                                            name="jenis_lapangan">
                                        <option value="" disabled selected hidden class="text-dark">Jenis Lapangan</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Search Button -->
                            <div class="col-md-3">
                                <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center rounded-3 py-0 fw-bold" 
                                        type="submit">
                                    Temukan 
                                    <span><i class='bx  bx-arrow-right bx-sm mt-2 ms-3' ></i> </span>
                                </button>
                            </div>

                        </div>
                    </form>
                    </div>
                </div>
                <hr>
                <div class="row">
                    @foreach ($gors as $gor )
                        <div class="col-lg-4 col-md-6 d-blok align-items-stretch mb-4" data-aos="zoom-in"
                            data-aos-delay="100">
                            <div class="card shadow-sm border-0">
                                <img src="{{ asset('storage/' . $gor->images->first()->image_path) }}" class="card-img-top"
                                    style="height: 200px; object-fit: cover;" alt="{{ $gor->nama_gor }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $gor->nama_gor }}</h5>
                                    <p class="card-text text-muted">{{ $gor->alamat_gor }}</p>
                                    <span class="badge bg-success">{{  $gor->wilayah }}</span>
                                    <a href="/gor/{{ $gor->slug_gor }}" class="btn btn-primary rounded-3 mt-2 w-100">Lihat
                                        Detail</a>
                                </div>
                            </div>

                        </div>
                    @endforeach
                    {!! $gors->links() !!}
                    <div class="d-flex">
                    </div>
                </div>
            </div>
        </section>


@endsection
@push('js_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const wilayahSelect = document.getElementById('search-wilayah');
    const kecamatanSelect = document.getElementById('search-kecamatan');
    const jenisLapanganSelect = document.getElementById('search-jenis-lapangan');

    let filterData = {}; // Variabel untuk menyimpan data dari API

    // 1. Ambil data filter dari API saat halaman dimuat
    fetch('/api/search-filters')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            filterData = data; // Simpan data ke variabel global
            
            // 2. Isi dropdown Wilayah
            const wilayahOptions = Object.keys(filterData.locations).sort();
            wilayahOptions.forEach(wilayah => {
                const option = new Option(wilayah, wilayah);
                wilayahSelect.add(option);
            });

            // 3. Isi dropdown Jenis Lapangan
            filterData.field_types.forEach(type => {
                const option = new Option(type, type);
                jenisLapanganSelect.add(option);
            });
        })
        .catch(error => {
            console.error('Error fetching search filters:', error);
            // Anda bisa menampilkan pesan error kepada pengguna di sini jika perlu
        });

    // 4. Tambahkan event listener untuk dropdown Wilayah
    wilayahSelect.addEventListener('change', function() {
        // Kosongkan dan nonaktifkan dropdown kecamatan terlebih dahulu
        kecamatanSelect.innerHTML = '<option value="" disabled selected hidden class="text-dark">Pilih Kecamatan</option>';
        kecamatanSelect.disabled = true;

        const selectedWilayah = this.value;

        if (selectedWilayah && filterData.locations[selectedWilayah]) {
            // Aktifkan dropdown kecamatan
            kecamatanSelect.disabled = false;
            
            // Isi dropdown kecamatan berdasarkan wilayah yang dipilih
            const kecamatanOptions = filterData.locations[selectedWilayah];
            kecamatanOptions.forEach(kecamatan => {
                const option = new Option(kecamatan, kecamatan);
                kecamatanSelect.add(option);
            });
        }
    });
});
</script>
@endpush

