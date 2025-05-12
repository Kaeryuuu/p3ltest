<?php

namespace App\Http\Controllers;

use App\Models\RequestDonasi;
use Illuminate\Http\Request;

class RequestDonasiController extends Controller
{
    public function index()
    {
        $requestDonasi = RequestDonasi::all();
        return response()->json($requestDonasi);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_permintaan' => 'nullable|date',
            'status' => 'nullable|string|max:20',
        ]);

        $requestDonasi = RequestDonasi::create($validated);
        return response()->json($requestDonasi, 201);
    }

    public function show($id)
    {
        $requestDonasi = RequestDonasi::findOrFail($id);
        return response()->json($requestDonasi);
    }

    public function update(Request $request, $id)
    {
        $requestDonasi = RequestDonasi::findOrFail($id);
        $validated = $request->validate([
            'tanggal_permintaan' => 'sometimes|nullable|date',
            'status' => 'sometimes|nullable|string|max:20',
        ]);

        $requestDonasi->update($validated);
        return response()->json($requestDonasi);
    }

    public function destroy($id)
    {
        $requestDonasi = RequestDonasi::findOrFail($id);
        $requestDonasi->delete();
        return response()->json(null, 204);
    }
}