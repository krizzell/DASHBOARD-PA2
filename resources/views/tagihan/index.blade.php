@extends('layouts.app')

@section('title', 'Data Tagihan')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h2><i class="bi bi-receipt"></i> Data Tagihan</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('tagihan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Buat Tagihan
        </a>
    </div>
</div>

@if ($tagihan->isEmpty())
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Tidak ada data tagihan
    </div>
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Siswa</th>
                        <th>Periode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tagihan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->siswa->nama_siswa ?? '-' }}</td>
                        <td>{{ $item->periode }}</td>
                        <td>Rp {{ number_format($item->jumlah_tagihan, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $statusLabels = [
                                    'belum_bayar' => 'Belum Bayar',
                                    'lunas' => 'Lunas'
                                ];
                                $badgeColor = $item->status == 'lunas' ? 'bg-success' : 'bg-warning';
                            @endphp
                            <span class="badge {{ $badgeColor }}">
                                {{ $statusLabels[$item->status] ?? ucfirst(str_replace('_', ' ', $item->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('tagihan.show', $item->id_tagihan) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> Lihat
                            </a>
                            <a href="{{ route('tagihan.edit', $item->id_tagihan) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('tagihan.destroy', $item->id_tagihan) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" data-delete-btn data-item-name="tagihan ini">
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
