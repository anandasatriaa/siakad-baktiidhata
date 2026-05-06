<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $active_periode = TahunAkademik::where('is_active', true)->first();
        $periode_id = $request->periode_id ?? ($active_periode->id ?? null);
        
        $kelas = Kelas::with(['wali_kelas', 'tahunAkademik'])
            ->when($periode_id, function($q) use ($periode_id) {
                return $q->where('tahun_akademik_id', $periode_id);
            })
            ->get();
            
        $periodes = TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();
        
        return view('admin.kelas.index', compact('kelas', 'periodes', 'periode_id', 'active_periode'));
    }

    public function create()
    {
        $active_periode = TahunAkademik::where('is_active', true)->first();
        $periodes = TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();
        
        if ($periodes->isEmpty()) {
            return redirect()->route('kelas.index')->with('error', 'Silakan buat data tahun akademik terlebih dahulu.');
        }

        $gurus = User::where('role', 'guru')->get();
            
        return view('admin.kelas.create', compact('gurus', 'active_periode', 'periodes'));
    }

    public function store(Request $request)
    {
        $periode_id = $request->tahun_akademik_id;
        
        $request->validate([
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'nama_kelas' => 'required|unique:kelas,nama_kelas,NULL,id,tahun_akademik_id,' . $periode_id,
            'tingkat' => 'required|integer',
            'wali_kelas_id' => 'nullable|exists:users,id|unique:kelas,wali_kelas_id,NULL,id,tahun_akademik_id,' . $periode_id,
        ], [
            'wali_kelas_id.unique' => 'Guru tersebut sudah menjadi wali kelas di kelas lain pada tahun akademik yang dipilih.',
            'nama_kelas.unique' => 'Nama kelas sudah terdaftar pada tahun akademik yang dipilih.',
        ]);

        Kelas::create($request->all());

        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil ditambahkan');
    }

    public function edit(Kelas $kela)
    {
        $gurus = User::where('role', 'guru')
            ->where(function ($query) use ($kela) {
                $query->whereDoesntHave('wali_kelas', function ($q) use ($kela) {
                    $q->where('tahun_akademik_id', $kela->tahun_akademik_id);
                })->orWhere('id', $kela->wali_kelas_id);
            })
            ->get();
        return view('admin.kelas.edit', ['kelas' => $kela, 'gurus' => $gurus]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => 'required|unique:kelas,nama_kelas,' . $kela->id . ',id,tahun_akademik_id,' . $kela->tahun_akademik_id,
            'tingkat' => 'required|integer',
            'wali_kelas_id' => 'nullable|exists:users,id|unique:kelas,wali_kelas_id,' . $kela->id . ',id,tahun_akademik_id,' . $kela->tahun_akademik_id,
        ], [
            'wali_kelas_id.unique' => 'Guru tersebut sudah menjadi wali kelas di kelas lain pada tahun akademik ini.',
            'nama_kelas.unique' => 'Nama kelas sudah terdaftar pada tahun akademik ini.',
        ]);

        $kela->update($request->all());

        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil diperbarui');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil dihapus');
    }
}
