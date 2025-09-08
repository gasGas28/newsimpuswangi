<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Farmasi\MasterObat;
use Illuminate\Http\Request;

class MasterObatController extends Controller
{
    // 📌 Ambil semua data obat
    public function index()
    {
        $obat = MasterObat::all();
        return response()->json($obat);
    }

    // 📌 Tambah data obat baru
    public function store(Request $request)
    {
        $request->validate([
            'obat_id' => 'required|integer|max:20',
            'kode_obat' => 'required|string|max:50',
            'nama' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:100',
            'jenis' => 'nullable|string|max:100',
            'golongan' => 'nullable|string|max:100',
            'sumber' => 'nullable|string|max:100',
            'tahun' => 'nullable|integer',
            'harga' => 'nullable|numeric',
            'isi' => 'nullable|string|max:50',
            'rek' => 'nullable|string|max:50',
            'aktif' => 'nullable|boolean',
            'created_date' => 'nullable|date',
        ]);

        $obat = MasterObat::create($request->all());

        return response()->json([
            'message' => 'Obat berhasil ditambahkan',
            'data' => $obat
        ], 201);
    }

    // 📌 Tampilkan detail obat berdasarkan id
    public function show($id)
    {
        $obat = MasterObat::findOrFail($id);
        return response()->json($obat, 200);
    }

    // 📌 Update data obat
    public function update(Request $request, $id)
    {
        $obat = MasterObat::findOrFail($id);

        $request->validate([
            'kode_obat' => 'sometimes|required|string|max:50',
            'nama' => 'sometimes|required|string|max:255',
            'satuan' => 'nullable|string|max:100',
            'jenis' => 'nullable|string|max:100',
            'golongan' => 'nullable|string|max:100',
            'sumber' => 'nullable|string|max:100',
            'tahun' => 'nullable|integer',
            'harga' => 'nullable|numeric',
            'isi' => 'nullable|string|max:50',
            'rek' => 'nullable|string|max:50',
            'aktif' => 'nullable|boolean',
            'created_date' => 'nullable|date',
        ]);

        $obat->update($request->all());

        return response()->json([
            'message' => 'Obat berhasil diperbarui',
            'data' => $obat
        ]);
    }

    // 📌 Hapus obat
    public function destroy($id)
    {
        MasterObat::destroy($id);
        return response()->json(['message' => 'Obat berhasil dihapus'], 200);
    }
}
