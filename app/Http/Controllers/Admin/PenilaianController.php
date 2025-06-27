<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $matakuliahs = DB::table('mata_kuliah')->select('id', 'nama_matkul')->get();

    //     return view('pages-admin.penilaian.nilai_mahasiswa_index', compact('matakuliahs'));
    // }
    public function index()
    {
        $matakuliahData = DB::table('mata_kuliah')->select('id', 'nama_matkul')->get();

        $data = [];

        foreach ($matakuliahData as $matakuliah) {
            $classes = DB::table('matakuliah_kelas')
                ->join('kelas', 'matakuliah_kelas.kelas_id', '=', 'kelas.id')
                ->join('dosen', 'matakuliah_kelas.dosen_id', '=', 'dosen.id')
                ->where('matakuliah_kelas.matakuliah_id', $matakuliah->id)
                ->select('kelas.nama_kelas', 'matakuliah_kelas.id', 'dosen.nama as nama_dosen')
                ->get();

            $studentsData = [];

            foreach ($classes as $class) {
                $students = DB::table('nilaiakhir_mahasiswa')
                    ->join('mahasiswa', 'nilaiakhir_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
                    ->where('nilaiakhir_mahasiswa.matakuliah_kelasid', $class->id)
                    ->select('mahasiswa.id as id_mahasiswa', 'mahasiswa.nim', 'mahasiswa.nama', 'nilaiakhir_mahasiswa.nilai_akhir as nilai_akhir')
                    ->orderBy('mahasiswa.nim')
                    ->paginate(5);

                $studentsData[$class->id] = $students;
            }

            $data[] = [
                'matakuliah' => $matakuliah,
                'classes' => $classes,
                'studentsData' => $studentsData,
            ];
        }

        return view('pages-admin.penilaian.nilai_mahasiswa', compact('data'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id_matkul)
    {
        $kelasData = DB::table('matakuliah_kelas')
            ->join('kelas', 'matakuliah_kelas.kelas_id', '=', 'kelas.id')
            ->join('dosen', 'matakuliah_kelas.dosen_id', '=', 'dosen.id')
            ->select('matakuliah_kelas.id as id_kelas_kuliah', 'kelas.nama_kelas', 'dosen.nama')
            ->where('matakuliah_kelas.matakuliah_id', '=', $id_matkul)
            ->get();
        // dd($kelasData);
        return view('pages-admin.penilaian.nilai_mahasiswa_show', compact('kelasData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
