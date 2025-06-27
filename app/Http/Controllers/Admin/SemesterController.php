<?php

namespace App\Http\Controllers\Admin;

use App\Models\Semester;
use App\Models\KelasKuliah;
use Illuminate\Http\Request;
use App\Models\NilaiMahasiswa;
use App\Models\NilaiAkhirMahasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SemesterController extends Controller
{
    public function index()
    {
        $query = Semester::select();

        $semester = $query->paginate(20);

        $startNumber = ($semester->currentPage() - 1) * $semester->perPage() + 1;


        return view('pages-admin.semester.semester', [
            'data' => $semester,
            'startNumber' => $startNumber,
        ])->with('success', 'Data Dosen Ditemukan');
    }

    public function create()
    {
        return view('pages-admin.semester.tambah_semester');
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'tahun_ajaran' => 'required',
            'semester' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
        }

        try {
            Semester::create([
                'tahun_ajaran' => $request->tahun_ajaran,
                'semester' => $request->semester,
                'is_active' => '0'
            ]);

            // return redirect()->route('admin.semester')->with('success', 'Data Semester Berhasil Ditambahkan');
            return response()->json(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal ditambahkan: ' . $e->getMessage()], 500);
            // return redirect()->back()->withErrors(['errors' => 'Data Gagal Ditambahkan: ' . $e->getMessage()])->withInput();
        }
    }

    public function updateIsActive(Request $request, int $id)
    {
        try {

            // $semester = Semester::where('id', $id)->first();
            $semesterToActivate = Semester::findOrFail($id);

            $currentActiveSemester = Semester::where('is_active', "1")->first();
            // dd($currentActiveSemester);

            if ($currentActiveSemester) {
                $currentActiveSemester->update(['is_active' => "0"]);
            }

            $semesterToActivate->update(['is_active' => $request->is_active]);
            // $semester->update([
            //     'is_active' => $request->is_active
            // ]);

            return redirect()->route('admin.semester')->with('success', 'Data Berhasil diupdate');
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getTrace()); // Tambahkan ini untuk melihat pesan kesalahan
            return redirect()->route('admin.semester')->with('error', 'Data Gagal Diupdate: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $semester = Semester::where('id', $id)->first();

        return view('pages-admin.semester.edit_semester', [
            'success' => 'Data Ditemukan',
            'data' => $semester,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validate = Validator::make($request->all(), [
            'tahun_ajaran' => 'required',
            'semester' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
        }

        try {
            $semester = Semester::where('id', $id)->first();

            $semester->update([
                'tahun_ajaran' => $request->tahun_ajaran,
                'semester' => $request->semester
            ]);

            // return redirect()->route('admin.semester')->with('success', 'Data Semester Berhasil Diubah');
            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate', 'data' => $semester]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal diupdate: ' . $e->getMessage()], 500);
            // return redirect()->back()->withErrors(['errors' => 'Data Gagal Diubah: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $matakuliah_kelas = KelasKuliah::where('semester_id', $id)->get();
            foreach ($matakuliah_kelas as $valueMatakuliah_Kelas) {
                NilaiMahasiswa::where('matakuliah_kelasid', $valueMatakuliah_Kelas->id)->delete();
                NilaiAkhirMahasiswa::where('matakuliah_kelasid', $valueMatakuliah_Kelas->id)->delete();
            }
            KelasKuliah::where('semester_id', $id)->delete();
            Semester::where('id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
            // return redirect()->route('admin.semester')
            //     ->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
            // return redirect()->route('admin.semester')
            //     ->with('error', 'Data gagal dihapus: ' . $e->getMessage());
        }
    }
}
