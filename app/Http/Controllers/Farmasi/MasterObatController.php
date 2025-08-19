<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\MasterObat;
use Illuminate\Http\Request;

class MasterObatController extends Controller
{
    // 📌 Ambil semua data obat
    public function index()
    {
        return response()->json(MasterObat::all(), 200);
    }

    // 📌 Tambah data obat baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_obat' => 'required|unique:master_obat',
            'nama_obat' => 'required|string|max:255',
            'satuan_obat' => 'required|string|max:50',
            'stok' => 'required|integer|min:0'
        ]);

        $obat = MasterObat::create($request->all());

        return response()->json($obat, 201);
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
            'nama_obat' => 'string|max:255',
            'satuan_obat' => 'string|max:50',
            'stok' => 'integer|min:0'
        ]);

        $obat->update($request->all());

        return response()->json($obat, 200);
    }

    // 📌 Hapus obat
    public function destroy($id)
    {
        MasterObat::destroy($id);
        return response()->json(['message' => 'Obat berhasil dihapus'], 200);
    }
}
