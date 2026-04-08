@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h2><i class="bi bi-person-badge"></i> Data Siswa</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('siswa.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Siswa
        </a>
    </div>
</div>

@if ($siswa->isEmpty())
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Tidak ada data siswa
    </div>
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Nama Orangtua</th>
                        <th>Kelas</th>
                        <th>Jenis Kelamin</th>
                        <th>Tgl Lahir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $item->nomor_induk_siswa }}</strong></td>
                        <td>{{ $item->nama_siswa }}</td>
                        <td>{{ $item->nama_orgtua }}</td>
                        <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                        <td>{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>{{ $item->tgl_lahir->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('siswa.show', $item->nomor_induk_siswa) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> Lihat
                            </a>
                            <a href="{{ route('siswa.edit', $item->nomor_induk_siswa) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('siswa.destroy', $item->nomor_induk_siswa) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" data-delete-btn data-item-name="siswa ini">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
