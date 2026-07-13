<?php

namespace Modules\Kerjasama\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Kerjasama\Models\Kerjasama;
use Modules\Kerjasama\Models\EvaluasiMitra;

class EvaluasiMitraController extends Controller
{
    public function store(Request $request, Kerjasama $kerjasama)
    {
        $validated = $request->validate([
            'tanggal_evaluasi' => 'required|date',
            'nilai' => 'required|integer|min:1|max:5',
            'catatan' => 'nullable|string',
        ]);

        $validated['kerjasama_id'] = $kerjasama->id;
        $validated['evaluator_id'] = auth()->id();

        EvaluasiMitra::create($validated);

        return redirect()->route('kerjasama.show', $kerjasama)->with('success', 'Evaluasi berhasil ditambahkan.');
    }

    public function destroy(Kerjasama $kerjasama, EvaluasiMitra $evaluasi)
    {
        $evaluasi->delete();

        return redirect()->route('kerjasama.show', $kerjasama)->with('success', 'Evaluasi berhasil dihapus.');
    }
}
