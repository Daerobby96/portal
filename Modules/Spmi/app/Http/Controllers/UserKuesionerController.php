<?php

namespace Modules\Spmi\Http\Controllers;
use App\Http\Controllers\Controller;

use Modules\Spmi\Models\Kuesioner;
use Modules\Spmi\Models\KuesionerJawaban;
use Modules\Spmi\Models\KuesionerJawabanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class UserKuesionerController extends Controller
{
    /**
     * Redirect ke kuesioner publik yang sedang aktif (Link Statis)
     */
    public function activeSurvey()
    {
        $active = Kuesioner::where('status', 'aktif')
            ->where('is_public', true)
            ->latest()
            ->first();

        if (!$active) {
            return redirect()->route('user-kuesioner.index')->with('error', 'Tidak ada kuesioner aktif saat ini.');
        }

        return redirect()->route('user-kuesioner.fill', $active);
    }

    public function index()
    {
        $userId = auth()->id();
        $userRole = auth()->check() ? auth()->user()->roles->first()?->name : null;

        // Ambil kuesioner yang aktif
        $query = Kuesioner::where('status', 'aktif');

        if (!auth()->check()) {
            // Jika tidak login, hanya tampilkan yang bersifat publik
            $query->where('is_public', true);
        } else {
            // Jika login, tampilkan yang sesuai role atau yang publik
            $query->where(function($q) use ($userRole) {
                $q->where('is_public', true)
                  ->orWhereNull('target_role')
                  ->orWhere('target_role', 'all')
                  ->orWhere('target_role', $userRole);
            });
        }

        $kuesioners = $query->withCount(['jawabans' => function($q) use ($userId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->whereRaw('1=0'); // Tidak bisa cek jawaban berdasarkan user_id jika guest
                }
            }])
            ->get();

        // Tambahkan status pengisian via Cookie untuk Guest
        foreach ($kuesioners as $k) {
            $cookieName = 'filled_kuesioner_' . $k->id;
            $k->is_filled_via_cookie = request()->cookie($cookieName) ? true : false;
        }

        return \Inertia\Inertia::render('Spmi/UserKuesioner/Index', [
            'kuesioners' => $kuesioners,
        ]);
    }

    public function fill(Kuesioner $kuesioner)
    {
        if ($kuesioner->status !== 'aktif') {
            return redirect()->route('user-kuesioner.index')->with('error', 'Kuesioner ini tidak aktif.');
        }

        // Proteksi pengisian ganda jika login
        if (auth()->check() && $kuesioner->isFilledBy(auth()->id())) {
            return redirect()->route('user-kuesioner.index')->with('error', 'Anda sudah mengisi kuesioner ini.');
        }
        
        $kuesioner->load('pertanyaans');

        $prodis = class_exists(\Modules\DataMaster\Models\ProgramStudi::class)
            ? \Modules\DataMaster\Models\ProgramStudi::where('is_aktif', true)->orderBy('jenjang')->orderBy('nama')->get(['id', 'nama', 'jenjang'])
            : [];

        return \Inertia\Inertia::render('Spmi/UserKuesioner/Fill', [
            'kuesioner' => $kuesioner,
            'prodis'    => $prodis,
        ]);
    }

    public function submit(Request $request, Kuesioner $kuesioner)
    {
        // Proteksi pengisian ganda jika login
        if (auth()->check() && $kuesioner->isFilledBy(auth()->id())) {
             return redirect()->route('user-kuesioner.index')->with('error', 'Anda sudah mengirim jawaban.');
        }

        $isExternalSurvey = in_array($kuesioner->id, [11, 12]) || str_contains(strtolower($kuesioner->judul), 'pengguna lulusan') || str_contains(strtolower($kuesioner->judul), 'mitra');

        $rules = [
            'kategori_responden' => 'nullable|string|max:100',
            'identitas_nomor'    => 'nullable|string|max:100',
            'program_studi'      => 'nullable|string|max:255',
            'angkatan_semester'  => 'nullable|string|max:100',
            'instansi'           => $isExternalSurvey ? 'required|string|max:255' : 'nullable|string|max:255',
            'jabatan'            => 'nullable|string|max:255',
            'email_responden'    => 'nullable|email|max:255',
            'no_hp_responden'    => 'nullable|string|max:50',
            'jawaban'            => 'required|array',
        ];

        if ($isExternalSurvey) {
            $rules['nama_responden'] = 'required|string|max:255';
        } else {
            $rules['nama_responden'] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        $namaResponden = $request->nama_responden;
        if ($request->boolean('is_anonymous') || empty(trim($namaResponden ?? ''))) {
            $namaResponden = 'Anonim (' . ($request->kategori_responden ?? 'Responden') . ')';
        }

        DB::transaction(function() use ($request, $kuesioner, $namaResponden) {
            $jawabanHeader = KuesionerJawaban::create([
                'kuesioner_id'       => $kuesioner->id,
                'user_id'            => auth()->id(), // NULL jika tamu/mahasiswa/tendik publik
                'nama_responden'     => $namaResponden,
                'identitas_nomor'    => $request->boolean('is_anonymous') ? null : $request->identitas_nomor,
                'kategori_responden' => $request->kategori_responden,
                'program_studi'      => $request->program_studi,
                'angkatan_semester'  => $request->angkatan_semester,
                'instansi'           => $request->instansi,
                'jabatan'            => $request->jabatan,
                'email_responden'    => $request->boolean('is_anonymous') ? null : $request->email_responden,
                'no_hp_responden'    => $request->boolean('is_anonymous') ? null : $request->no_hp_responden,
                'filled_at'          => now(),
            ]);

            foreach ($request->jawaban as $pertanyaanId => $val) {
                KuesionerJawabanDetail::create([
                    'jawaban_id'    => $jawabanHeader->id,
                    'pertanyaan_id' => $pertanyaanId,
                    'skor'          => is_numeric($val) ? $val : null,
                    'jawaban_text'  => !is_numeric($val) ? $val : null,
                ]);
            }
        });

        $cookieName = 'filled_kuesioner_' . $kuesioner->id;

        return redirect()->route('user-kuesioner.index')
            ->with('success', 'Terima kasih! Jawaban kuesioner Anda telah berhasil tersimpan dengan aman.')
            ->withCookie(cookie($cookieName, 'true', 43200)); 
    }
}
