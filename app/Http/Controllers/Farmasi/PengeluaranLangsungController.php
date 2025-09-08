<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranLangsung;
use Illuminate\Http\Request;

class PengeluaranLangsungController extends Controller
{
    /**
     * Tampilkan semua data resep langsung.
     */
    public function index()
    {
        return response()->json(
            PengeluaranLangsung::orderBy('tanggal', 'desc')->get()
        );
    }

    /**
     * Simpan data baru resep langsung.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'       => 'required|date',
            'unitId'        => 'nullable|integer',
            'pengMasterId'  => 'nullable|integer',
            'keterangan'    => 'nullable|string',
            'obat_id'       => 'required|integer',
            'kode_obat'     => 'required|string|max:50',
            'nama_obat'     => 'required|string|max:150',
            'jumlah'        => 'required|integer',
            'createdDate'   => 'nullable|date',
            'createdBy'     => 'nullable|string|max:50',
            'modified_date' => 'nullable|date',
            'modified_by'   => 'nullable|string|max:50',
        ]);

        $data = PengeluaranLangsung::create($validated);

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $data
        ], 201);
    }

    /**
     * Menampilkan data detail berdasarkan ID.
     */
    public function show($id)
    {
        $data = PengeluaranLangsung::findOrFail($id);
        return response()->json($data);
    }

    /**
     * Update data resep langsung.
     */
    public function update(Request $request, $id)
    {
        $data = PengeluaranLangsung::findOrFail($id);
        $data->update($request->all());

        
        return response()->json([
            'message' => 'Data berhasil diperbarui',
            'data' => $data
        ]);
    }

    /**
     * Hapus data resep langsung.
     */
    public function destroy($id)
    {
        $data = PengeluaranLangsung::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
