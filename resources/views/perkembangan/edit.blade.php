@extends('layouts.app')

@section('title', 'Edit Perkembangan')

@section('content')
<div class="row">
    <div class="col-md-10">
        <h2><i class="bi bi-pencil"></i> Edit Perkembangan Anak</h2>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form action="{{ route('perkembangan.update', $perkembangan->id_perkembangan) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Info Anak & Guru -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="nomor_induk_siswa" class="form-label">Siswa / Anak <span class="text-danger">*</span></label>
                    <select class="form-control @error('nomor_induk_siswa') is-invalid @enderror" id="nomor_induk_siswa" name="nomor_induk_siswa" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($siswa as $s)
                            <option value="{{ $s->nomor_induk_siswa }}" {{ old('nomor_induk_siswa', $perkembangan->nomor_induk_siswa) == $s->nomor_induk_siswa ? 'selected' : '' }}>
                                {{ $s->nama_siswa }} ({{ $s->kelas->nama_kelas ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('nomor_induk_siswa')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Periode Laporan -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="bulan" class="form-label">Bulan <span class="text-danger">*</span></label>
                    <select class="form-control @error('bulan') is-invalid @enderror" id="bulan" name="bulan" required>
                        <option value="">-- Pilih Bulan --</option>
                        <option value="1" {{ old('bulan', $perkembangan->bulan) == 1 ? 'selected' : '' }}>Januari</option>
                        <option value="2" {{ old('bulan', $perkembangan->bulan) == 2 ? 'selected' : '' }}>Februari</option>
                        <option value="3" {{ old('bulan', $perkembangan->bulan) == 3 ? 'selected' : '' }}>Maret</option>
                        <option value="4" {{ old('bulan', $perkembangan->bulan) == 4 ? 'selected' : '' }}>April</option>
                        <option value="5" {{ old('bulan', $perkembangan->bulan) == 5 ? 'selected' : '' }}>Mei</option>
                        <option value="6" {{ old('bulan', $perkembangan->bulan) == 6 ? 'selected' : '' }}>Juni</option>
                        <option value="7" {{ old('bulan', $perkembangan->bulan) == 7 ? 'selected' : '' }}>Juli</option>
                        <option value="8" {{ old('bulan', $perkembangan->bulan) == 8 ? 'selected' : '' }}>Agustus</option>
                        <option value="9" {{ old('bulan', $perkembangan->bulan) == 9 ? 'selected' : '' }}>September</option>
                        <option value="10" {{ old('bulan', $perkembangan->bulan) == 10 ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ old('bulan', $perkembangan->bulan) == 11 ? 'selected' : '' }}>November</option>
                        <option value="12" {{ old('bulan', $perkembangan->bulan) == 12 ? 'selected' : '' }}>Desember</option>
                    </select>
                    @error('bulan')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('tahun') is-invalid @enderror" 
                           id="tahun" name="tahun" value="{{ old('tahun', $perkembangan->tahun) }}" 
                           min="2020" max="2099" required>
                    @error('tahun')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>

            <!-- Kategori -->
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('kategori') is-invalid @enderror" 
                       id="kategori" name="kategori" value="{{ old('kategori', $perkembangan->kategori) }}" 
                       placeholder="Misal: Akademik, Sosial, Emosional, Motorik, dll" required>
                @error('kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Indikator Capaian -->
            <div class="mb-4">
                <label class="form-label">Indikator Capaian <span class="text-danger">*</span></label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="status_bb" name="status_utama" value="BB" 
                               {{ old('status_utama', $perkembangan->status_utama) == 'BB' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_bb">
                            <span class="badge bg-danger">BB</span> Belum Berkembang
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="status_mb" name="status_utama" value="MB" 
                               {{ old('status_utama', $perkembangan->status_utama) == 'MB' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_mb">
                            <span class="badge bg-warning text-dark">MB</span> Mulai Berkembang
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="status_bsh" name="status_utama" value="BSH" 
                               {{ old('status_utama', $perkembangan->status_utama) == 'BSH' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_bsh">
                            <span class="badge bg-info">BSH</span> Sesuai Harapan
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="status_bsb" name="status_utama" value="BSB" 
                               {{ old('status_utama', $perkembangan->status_utama) == 'BSB' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_bsb">
                            <span class="badge bg-success">BSB</span> Sangat Baik
                        </label>
                    </div>
                </div>
                @error('status_utama')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Deskripsi Perkembangan -->
            <div class="mb-4">
                <label for="deskripsi" class="form-label">Deskripsi Perkembangan <span class="text-danger">*</span></label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                          id="deskripsi" name="deskripsi" rows="5" placeholder="Penjelasan detail tentang perkembangan anak...">{{ old('deskripsi', $perkembangan->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Perbarui
                </button>
                <a href="{{ route('perkembangan.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .border-danger { border-left: 4px solid #dc3545 !important; }
    .border-warning { border-left: 4px solid #ffc107 !important; }
    .border-info { border-left: 4px solid #0dcaf0 !important; }
    .border-success { border-left: 4px solid #198754 !important; }
</style>
@endsection
