@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        <h3>Edit My GOR Details: {{ $gor->nama_gor }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit My GOR</li>
            </ol>
        </nav>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-body">
                    @include('backend.partials.alerts') {{-- Gunakan partials alert yang sama --}}

                    <form action="{{ route('admin.gor.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_gor" class="form-label">GOR Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_gor') is-invalid @enderror" id="nama_gor" name="nama_gor" value="{{ old('nama_gor', $gor->nama_gor) }}" required>
                                    @error('nama_gor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="alamat_gor" class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alamat_gor') is-invalid @enderror" id="alamat_gor" name="alamat_gor" rows="3" required>{{ old('alamat_gor', $gor->alamat_gor) }}</textarea>
                                    @error('alamat_gor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea style="min-height: 280px;" class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $gor->deskripsi ?? '') }}</textarea>
                                    <div class="form-text">Deskripsikan Lapanganmu untuk pengguna</div>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="latitude" class="form-label">Latitude</label>
                                    <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $gor->latitude) }}">
                                    @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="longitude" class="form-label">Longitude</label>
                                    <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $gor->longitude) }}">
                                    @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="wilayah" class="form-label">Wilayah</label>
                                    <select class="form-select" name="wilayah" id="wilayah">
                                        <option value="" disabled selected>Pilih Wilayah</option>
                                        <option value="Jakarta Pusat" {{ old('wilayah', $gor->wilayah ?? '') == 'Jakarta Pusat' ? 'selected' : '' }}>Jakarta Pusat</option>
                                        <option value="Jakarta Timur" {{ old('wilayah', $gor->wilayah ?? '') == 'Jakarta Timur' ? 'selected' : '' }}>Jakarta Timur</option>
                                        <option value="Jakarta Barat" {{ old('wilayah', $gor->wilayah ?? '') == 'Jakarta Barat' ? 'selected' : '' }}>Jakarta Barat</option>
                                        <option value="Jakarta Selatan" {{ old('wilayah', $gor->wilayah ?? '') == 'Jakarta Selatan' ? 'selected' : '' }}>Jakarta Selatan</option>
                                        <option value="Jakarta Utara" {{ old('wilayah', $gor->wilayah ?? '') == 'Jakarta Utara' ? 'selected' : '' }}>Jakarta Utara</option>
                                    </select>
                                    @error('wilayah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="kecamatan" class="form-label">Kecamatan</label>
                                    <select class="form-select @error('kecamatan') is-invalid @enderror" id="kecamatan" name="kecamatan">
                                        <option value="">Pilih Wilayah Terlebih Dahulu</option>
                                    </select>
                                    @error('kecamatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr>
                                <h5 class="mb-3">Kontak & Media Sosial</h5>

                                <div class="mb-3">
                                    <label for="whatsapp" class="form-label">Nomor WhatsApp</label>
                                    <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $gor->whatsapp ?? '') }}" placeholder="Contoh: 6281234567890">
                                    @error('whatsapp')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="instagram" class="form-label">Username Instagram</label>
                                    <input type="text" class="form-control @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="{{ old('instagram', $gor->instagram ?? '') }}" placeholder="Tanpa @">
                                    @error('instagram')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                               <label for="fasilitas" class="mb-2">Fasilitas</label>
                               <textarea class="summernote @error('fasilitas') is-invalid @enderror" id="summernote" name="fasilitas" rows="4">{{ old('fasilitas', $gor->fasilitas) }}</textarea>
                               <div class="form-text">Note : Disarankan menggunakan format list</div>
                               @error('fasilitas')
                                   <div class="invalid-feedback">{{ $message }}</div>
                               @enderror
                           </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update GOR Details</button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                    <hr>

                    <h5>Current Images</h5>
                    <div class="row mb-3">
                        @forelse($gor->images as $image)
                            <div class="col-md-3 col-6 mb-3 text-center">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="GOR Image" class="img-thumbnail mb-2" style="max-height: 150px; width:100%; object-fit:cover;">
                                <form action="{{ route('admin.gor.image.delete', $image->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete Image</button>
                                </form>
                            </div>
                        @empty
                            <div class="col-12">
                                <p>No images uploaded yet.</p>
                            </div>
                        @endforelse
                    </div>

                    <hr>
                    <h5>Add More GOR Images</h5>
                     <form action="{{ route('admin.gor.images.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="images" class="form-label">Select Images (Can select multiple)</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror @error('images') is-invalid @enderror" id="images" name="images[]" multiple required>
                            <small class="form-text text-muted">Max file size 2MB each. Allowed types: jpg, jpeg, png, gif.</small>
                            @error('images.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            @error('images') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-success">Upload New Images</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wilayahSelect = document.getElementById('wilayah');
        const kecamatanSelect = document.getElementById('kecamatan');

        // Simpan nilai kecamatan yang sudah ada di database
        const savedKecamatan = "{{ old('kecamatan', $gor->kecamatan ?? '') }}";

        function fetchKecamatan(selectedWilayah) {
            // Jika tidak ada wilayah yang dipilih, kosongkan dan nonaktifkan kecamatan
            if (!selectedWilayah) {
                kecamatanSelect.innerHTML = '<option value="">Pilih Wilayah Terlebih Dahulu</option>';
                kecamatanSelect.disabled = true;
                return;
            }

            // Ambil data dari server
            fetch(`/api/kecamatan/${encodeURIComponent(selectedWilayah)}`)
                .then(response => response.json())
                .then(data => {
                    // Kosongkan opsi sebelumnya
                    kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                    
                    // Isi dengan opsi baru
                    data.forEach(function (kecamatan) {
                        const option = document.createElement('option');
                        option.value = kecamatan;
                        option.textContent = kecamatan;

                        // Jika nilai kecamatan sama dengan yang tersimpan, jadikan 'selected'
                        if (kecamatan === savedKecamatan) {
                            option.selected = true;
                        }

                        kecamatanSelect.appendChild(option);
                    });

                    // Aktifkan kembali dropdown kecamatan
                    kecamatanSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error fetching kecamatan:', error);
                    kecamatanSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                });
        }

        // Tambahkan event listener untuk perubahan pada dropdown wilayah
        wilayahSelect.addEventListener('change', function () {
            fetchKecamatan(this.value);
        });

        // Panggil fungsi saat halaman pertama kali dimuat, untuk mengisi dropdown
        // kecamatan jika wilayah sudah terpilih (untuk kasus edit)
        if (wilayahSelect.value) {
            fetchKecamatan(wilayahSelect.value);
        } else {
            kecamatanSelect.disabled = true;
        }
    });
</script>
@endpush