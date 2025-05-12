<?php

namespace App\Http\Controllers;

use App\Models\TopSeller;
use Illuminate\Http\Request;

class TopSellerController extends Controller
{
    public function index()
    {
        $topSeller = TopSeller::all();
        return response()->json($topSeller);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
        ]);

        $topSeller = TopSeller::create($validated);
        return response()->json($topSeller, 201);
    }

    public function show($id)
    {
        $topSeller = TopSeller::findOrFail($id);
        return response()->json($topSeller);
    }

    public function update(Request $request, $id)
    {
        $topSeller = TopSeller::findOrFail($id);
        $validated = $request->validate([
            'tanggal_mulai' => 'sometimes|nullable|date',
            'tanggal_selesai' => 'sometimes|nullable|date',
        ]);

        $topSeller->update($validated);
        return response()->json($topSeller);
    }

    public function destroy($id)
    {
        $topSeller = TopSeller::findOrFail($id);
        $topSeller->delete();
        return response()->json(null, 204);
    }
}