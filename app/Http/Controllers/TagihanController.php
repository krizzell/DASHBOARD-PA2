<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Siswa;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
    {
        $tagihan = Tagihan::with('siswa')->get();
        return view('tagihan.index', compact('tagihan'));
    }

    public function create()
    {
        $siswa = Siswa::all();
        return view('tagihan.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_induk_siswa' => 'required|exists:siswa,nomor_induk_siswa',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'periode' => 'required|string|max:20',
            'status' => 'required|in:belum_bayar,lunas',
        ]);

        Tagihan::create($validated);
        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil ditambahkan');
    }

    public function show(Tagihan $tagihan)
    {
        return view('tagihan.show', compact('tagihan'));
    }

    public function edit(Tagihan $tagihan)
    {
        $siswa = Siswa::all();
        return view('tagihan.edit', compact('tagihan', 'siswa'));
    }

    public function update(Request $request, Tagihan $tagihan)
    {
        $validated = $request->validate([
            'nomor_induk_siswa' => 'required|exists:siswa,nomor_induk_siswa',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'periode' => 'required|string|max:20',
            'status' => 'required|in:belum_bayar,lunas',
        ]);

        $tagihan->update($validated);
        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil diperbarui');
    }

    public function destroy(Tagihan $tagihan)
    {
        $tagihan->delete();
        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil dihapus');
    }
}
