@extends('auth.layouts.main')
@section('container')
    {{-- container --}}
    <div class="container">
        {{-- mt-5 --}}
        <form class="needs-validation col-12 mt-5" action="/orderStore" method="post">
            @csrf
            {{-- mt-5 --}}
            <div class="row g-3 mt-5">
                {{-- mt-5 --}}
                <div class="col-lg-12 mt-5">
                    <span class="hidden">{{ $gor->id }}</span>
                    <label for="field_id" class="form-label">Pilih Lapangan</label>
                    <select class="form-select  @error('field_id') is-invalid @enderror" id="field_id" nama="field_id"
                        required>
                        <option value="">--Pilih Lapangan--</option>
                        @foreach ($gor->field as $field)
                            <option value="{{ $field->field_id }}" data-harga="{{ $field->harga_sewa }}">
                                {{ $field->nama_lapangan }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        @error('field_id')
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
                    <select class="form-select @error('jam_mulai') is-invalid @enderror" id="jam_mulai" name="jam_mulai"
                        required>
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
                    <label for="jam_selesai" class="form-label">Jam Selesai</label>
                    <select class="form-select  @error('jam_selesai') is-invalid @enderror" id="jam_selesai"
                        name="jam_selesai" required>
                        <option value="">--Pilih Jam Selesai--</option>
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
                        <option value="23:00">23:00</option>
                    </select>
                    @error('namaLapangan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <hr class="my-4">
                <div class="form-check">
                    <span class="text-success">Harga</span>
                    <h4 class="mb-3">Rp.0 Ribu</h4>
                </div>
                <hr class="my-4">
                <button class="w-100 btn btn-success btn-lg" type="submit" data-bs-toggle="modal"
                    data-bs-target="#staticBackdrop">Checkout
                </button>
            </div>
        </form>
        </section>
    @endsection
