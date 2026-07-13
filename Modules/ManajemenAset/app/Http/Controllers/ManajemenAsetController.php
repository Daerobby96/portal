<?php

namespace Modules\ManajemenAset\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManajemenAsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats = [
            'total_aset' => \Modules\ManajemenAset\Models\Aset::count(),
            'aktif' => \Modules\ManajemenAset\Models\Aset::where('status', 'aktif')->count(),
            'rusak' => \Modules\ManajemenAset\Models\Aset::whereIn('kondisi', ['rusak_ringan', 'rusak_berat'])->count(),
            'perbaikan' => \Modules\ManajemenAset\Models\Aset::where('status', 'dalam_perbaikan')->count(),
        ];
        return view('manajemenaset::index', compact('stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manajemenaset::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('manajemenaset::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('manajemenaset::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
