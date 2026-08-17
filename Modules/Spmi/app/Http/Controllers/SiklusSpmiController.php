<?php

namespace Modules\Spmi\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Modules\DataMaster\Models\Periode;
use Modules\Spmi\Models\SiklusSpmi;
use Illuminate\Http\Request;

class SiklusSpmiController extends Controller
{
    public function index()
    {
        $sikluses = SiklusSpmi::with(['penanggungJawab', 'periodes'])
            ->orderByDesc('tahun_siklus')
            ->get();

        return view('spmi::siklus-spmi.index', compact('sikluses'));
    }

    public function create()
    {
        $periodes = Periode::orderByDesc('tahun')->get();
        $users    = User::where('is_active', true)->orderBy('name')->get();
        return view('spmi::siklus-spmi.create', compact('periodes', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'                 => 'required|string|max:255',
            'tahun_siklus'         => 'required|integer|min:2000|max:2099',
            'tanggal_mulai'        => 'required|date',
            'tanggal_selesai'      => 'nullable|date|after_or_equal:tanggal_mulai',
            'status'               => 'required|in:persiapan,berjalan,evaluasi,ditutup',
            'deskripsi'            => 'nullable|string',
            'penanggung_jawab_id'  => 'nullable|exists:users,id',
            'is_aktif'             => 'boolean',
            'periode_ids'          => 'nullable|array',
            'periode_ids.*'        => 'exists:periodes,id',
        ]);

        // If marking as active, deactivate others first
        if (!empty($validated['is_aktif'])) {
            SiklusSpmi::where('is_aktif', true)->update(['is_aktif' => false]);
        }

        $siklus = SiklusSpmi::create($validated);

        // Attach selected periodes
        if (!empty($validated['periode_ids'])) {
            Periode::whereIn('id', $validated['periode_ids'])
                ->update(['siklus_spmi_id' => $siklus->id]);
        }

        return redirect()->route('siklus-spmi.show', $siklus)
            ->with('success', 'Siklus Mutu berhasil dibuat.');
    }

    public function show(SiklusSpmi $siklusSpmi)
    {
        $siklusSpmi->load(['penanggungJawab', 'periodes']);

        $ppepp = $siklusSpmi->ppepp_aggregate;

        // All periods for adding to this cycle
        $availablePeriodes = Periode::whereNull('siklus_spmi_id')
            ->orWhere('siklus_spmi_id', $siklusSpmi->id)
            ->orderByDesc('tahun')->get();

        // Previous cycles for comparison chart
        $previousCycles = SiklusSpmi::where('id', '!=', $siklusSpmi->id)
            ->whereIn('status', ['evaluasi', 'ditutup'])
            ->orderBy('tahun_siklus')
            ->get()
            ->map(fn($s) => [
                'nama'    => $s->nama,
                'overall' => $s->status === 'ditutup' && $s->snapshot_ppepp
                    ? ($s->snapshot_ppepp['overall'] ?? 0)
                    : $s->ppepp_aggregate['overall'],
                'ppepp'   => $s->status === 'ditutup' && $s->snapshot_ppepp
                    ? $s->snapshot_ppepp
                    : $s->ppepp_aggregate,
            ]);

        return view('spmi::siklus-spmi.show', compact('siklusSpmi', 'ppepp', 'availablePeriodes', 'previousCycles'));
    }

    public function edit(SiklusSpmi $siklusSpmi)
    {
        $periodes = Periode::orderByDesc('tahun')->get();
        $users    = User::where('is_active', true)->orderBy('name')->get();
        return view('spmi::siklus-spmi.edit', compact('siklusSpmi', 'periodes', 'users'));
    }

    public function update(Request $request, SiklusSpmi $siklusSpmi)
    {
        $validated = $request->validate([
            'nama'                => 'required|string|max:255',
            'tahun_siklus'        => 'required|integer|min:2000|max:2099',
            'tanggal_mulai'       => 'required|date',
            'tanggal_selesai'     => 'nullable|date|after_or_equal:tanggal_mulai',
            'status'              => 'required|in:persiapan,berjalan,evaluasi,ditutup',
            'deskripsi'           => 'nullable|string',
            'penanggung_jawab_id' => 'nullable|exists:users,id',
            'is_aktif'            => 'boolean',
            'periode_ids'         => 'nullable|array',
            'periode_ids.*'       => 'exists:periodes,id',
        ]);

        if (!empty($validated['is_aktif'])) {
            SiklusSpmi::where('id', '!=', $siklusSpmi->id)
                ->where('is_aktif', true)
                ->update(['is_aktif' => false]);
        }

        $siklusSpmi->update($validated);

        // Detach old periodes from this cycle
        Periode::where('siklus_spmi_id', $siklusSpmi->id)
            ->update(['siklus_spmi_id' => null]);

        // Attach newly selected periodes
        if (!empty($validated['periode_ids'])) {
            Periode::whereIn('id', $validated['periode_ids'])
                ->update(['siklus_spmi_id' => $siklusSpmi->id]);
        }

        return redirect()->route('siklus-spmi.show', $siklusSpmi)
            ->with('success', 'Siklus Mutu berhasil diperbarui.');
    }

    public function destroy(SiklusSpmi $siklusSpmi)
    {
        if ($siklusSpmi->status !== 'persiapan') {
            return back()->with('error', 'Hanya siklus dengan status Persiapan yang dapat dihapus.');
        }

        // Detach periodes
        Periode::where('siklus_spmi_id', $siklusSpmi->id)->update(['siklus_spmi_id' => null]);
        $siklusSpmi->delete();

        return redirect()->route('siklus-spmi.index')->with('success', 'Siklus Mutu berhasil dihapus.');
    }

    /**
     * Officially close the cycle: take a snapshot of PPEPP scores and set status to 'ditutup'.
     */
    public function close(SiklusSpmi $siklusSpmi)
    {
        if ($siklusSpmi->status === 'ditutup') {
            return back()->with('error', 'Siklus ini sudah ditutup.');
        }

        $snapshot = $siklusSpmi->ppepp_aggregate;

        $siklusSpmi->update([
            'status'          => 'ditutup',
            'tanggal_selesai' => now()->toDateString(),
            'snapshot_ppepp'  => $snapshot,
            'is_aktif'        => false,
        ]);

        return redirect()->route('siklus-spmi.show', $siklusSpmi)
            ->with('success', 'Siklus Mutu resmi ditutup. Snapshot PPEPP telah disimpan.');
    }
}
