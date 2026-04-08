<?php

namespace App\Http\Controllers;

use App\Models\Perkembangan;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class PerkembanganController extends Controller
{
    public function index()
    {
        // Semua guru (termasuk superadmin) bisa melihat SEMUA perkembangan anak
        $perkembangan = Perkembangan::with(['guru', 'siswa'])->get();
        return view('perkembangan.index', compact('perkembangan'));
    }

    public function create()
    {
        // Pastikan user adalah guru dan punya id_guru
        if (!session('id_guru')) {
            return redirect()->route('perkembangan.index')->with('error', 
                'Akun Anda tidak terhubung dengan data guru. ' .
                'Hubungi super admin untuk link akun Anda dengan guru.'
            );
        }

        // Tampilkan SEMUA siswa yang ada
        $siswa = Siswa::with('kelas')->get();

        return view('perkembangan.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        if (!session('id_guru')) {
            return redirect()->route('perkembangan.index')->with('error', 
                'Anda tidak berwenang menambah perkembangan. Hanya guru yang dapat menambah perkembangan.'
            );
        }

        $validated = $request->validate([
            'nomor_induk_siswa' => 'required|exists:siswa,nomor_induk_siswa',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2099',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'status_utama' => 'required|in:BB,MB,BSH,BSB',
        ]);

        // Auto-set guru dari session
        $validated['id_guru'] = session('id_guru');

        Perkembangan::create($validated);
        return redirect()->route('perkembangan.index')->with('success', 'Perkembangan berhasil ditambahkan');
    }

    public function show(Perkembangan $perkembangan)
    {
        return view('perkembangan.show', compact('perkembangan'));
    }

    public function edit(Perkembangan $perkembangan)
    {
        // Super admin bisa edit semua, regular guru bisa edit:
        // 1. Perkembangan untuk siswa di kelasnya
        // 2. Perkembangan yang mereka buat sendiri
        if (!session('is_super_admin')) {
            $kelasGuruArray = Kelas::where('id_guru', session('id_guru'))->pluck('id_kelas')->toArray();
            $siswaGuruArray = !empty($kelasGuruArray) ? Siswa::whereIn('id_kelas', $kelasGuruArray)->pluck('nomor_induk_siswa')->toArray() : [];
            
            $isOwnCreation = $perkembangan->id_guru == session('id_guru');
            $isKelasStudent = in_array($perkembangan->nomor_induk_siswa, $siswaGuruArray);
            
            if (!$isOwnCreation && !$isKelasStudent) {
                return redirect()->route('perkembangan.index')->with('error', 
                    'Anda tidak berwenang mengedit perkembangan ini.'
                );
            }
        }

        // Tampilkan SEMUA siswa yang ada
        $siswa = Siswa::with('kelas')->get();

        return view('perkembangan.edit', compact('perkembangan', 'siswa'));
    }

    public function update(Request $request, Perkembangan $perkembangan)
    {
        // Super admin bisa edit semua, regular guru bisa edit:
        // 1. Perkembangan untuk siswa di kelasnya
        // 2. Perkembangan yang mereka buat sendiri
        if (!session('is_super_admin')) {
            $kelasGuruArray = Kelas::where('id_guru', session('id_guru'))->pluck('id_kelas')->toArray();
            $siswaGuruArray = !empty($kelasGuruArray) ? Siswa::whereIn('id_kelas', $kelasGuruArray)->pluck('nomor_induk_siswa')->toArray() : [];
            
            $isOwnCreation = $perkembangan->id_guru == session('id_guru');
            $isKelasStudent = in_array($perkembangan->nomor_induk_siswa, $siswaGuruArray);
            
            if (!$isOwnCreation && !$isKelasStudent) {
                return redirect()->route('perkembangan.index')->with('error', 
                    'Anda tidak berwenang mengedit perkembangan ini.'
                );
            }
        }

        $validated = $request->validate([
            'nomor_induk_siswa' => 'required|exists:siswa,nomor_induk_siswa',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2099',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'status_utama' => 'required|in:BB,MB,BSH,BSB',
        ]);

        // Jangan ubah id_guru, tetap gunakan guru yang asli (untuk audit trail)
        $perkembangan->update($validated);
        return redirect()->route('perkembangan.index')->with('success', 'Perkembangan berhasil diperbarui');
    }

    public function destroy(Perkembangan $perkembangan)
    {
        // Super admin bisa hapus semua, regular guru hanya untuk:
        // 1. Perkembangan untuk siswa di kelasnya
        // 2. Perkembangan yang mereka buat sendiri
        if (!session('is_super_admin')) {
            $kelasGuruArray = Kelas::where('id_guru', session('id_guru'))->pluck('id_kelas')->toArray();
            $siswaGuruArray = !empty($kelasGuruArray) ? Siswa::whereIn('id_kelas', $kelasGuruArray)->pluck('nomor_induk_siswa')->toArray() : [];
            
            $isOwnCreation = $perkembangan->id_guru == session('id_guru');
            $isKelasStudent = in_array($perkembangan->nomor_induk_siswa, $siswaGuruArray);
            
            if (!$isOwnCreation && !$isKelasStudent) {
                return redirect()->route('perkembangan.index')->with('error', 
                    'Anda tidak berwenang menghapus perkembangan ini.'
                );
            }
        }

        $perkembangan->delete();
        return redirect()->route('perkembangan.index')->with('success', 'Perkembangan berhasil dihapus');
    }
}
