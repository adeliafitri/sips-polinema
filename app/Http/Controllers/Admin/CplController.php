<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cpl;
use App\Models\Cpmk;
use App\Models\SubCpmk;
use App\Models\KelasKuliah;
use App\Models\SoalSubCpmk;
use Illuminate\Http\Request;
use App\Models\NilaiMahasiswa;
use App\Exports\CplFormatExcel;
use App\Models\NilaiAkhirMahasiswa;
use App\Http\Controllers\Controller;
use App\Models\Rps;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class CplController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Cpl::query();

        // Cek apakah ada parameter pencarian
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('cpl.kode_cpl', 'like', '%' . $searchTerm . '%')
                    ->orWhere('cpl.jenis_cpl', 'like', '%' . $searchTerm . '%')
                    ->orWhere('cpl.deskripsi', 'like', '%' . $searchTerm . '%');
            });
        }

        $cpl = $query->paginate(20);

        $startNumber = ($cpl->currentPage() - 1) * $cpl->perPage() + 1;

        return view('pages-admin.cpl.cpl', [
            'data' => $cpl,
            'startNumber' => $startNumber,
        ])->with('success', 'Data cpl Ditemukan');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages-admin.cpl.tambah_cpl');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'kode_cpl' => 'required|string',
            'deskripsi' => 'required',
            'jenis_cpl' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
        }

        try {
            Cpl::create([
                'kode_cpl' => $request->kode_cpl,
                'deskripsi' => $request->deskripsi,
                'jenis_cpl' => $request->jenis_cpl,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
            // return redirect()->route('admin.cpl')->with('success', 'Data Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cpl = Cpl::find($id);

        return view('pages-admin.cpl.edit_cpl', [
            'success' => 'Data Ditemukan',
            'data' => $cpl,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'kode_cpl' => 'required|string',
            'deskripsi' => 'required',
            'jenis_cpl' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
        }

        try {
            $cpl = Cpl::find($id);
            $cpl->update([
                'kode_cpl' => $request->kode_cpl,
                'deskripsi' => $request->deskripsi,
                'jenis_cpl' => $request->jenis_cpl,
            ]);

            // return redirect()->route('admin.cpl')->with([
            //     'success' => 'Data Berhasil Diupdate',
            //     'data' => $cpl
            // ]);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate', 'data' => $cpl]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal diupdate: ' . $e->getMessage()], 500);
            // dd($e->getMessage(), $e->getTrace()); // Tambahkan ini untuk melihat pesan kesalahan
            // return redirect()->route('admin.cpl.edit', $id)->with('error', 'Data Gagal Diupdate: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        try {

            $cpmk = Cpmk::where('cpl_id', $id)->get();
            foreach ($cpmk as $valueCpmk) {
                $subcpmk = SubCpmk::where('cpmk_id', $valueCpmk->id)->select('id')->get();
                foreach ($subcpmk as $valueSubCpmk) {
                    $soalsubcpmk = SoalSubCpmk::where('subcpmk_id', $valueSubCpmk->id)->select('id')->get();
                    foreach ($soalsubcpmk as $valueSoal) {
                        NilaiMahasiswa::where('soal_id', $valueSoal->id)->delete();
                        $matakuliah_kelas = KelasKuliah::where('rps_id', $valueCpmk->rps_id)->select('id')->get();
                        foreach ($matakuliah_kelas as $valueMatakuliah_Kelas) {
                            $nilaiAkhirMahasiswa = NilaiAkhirMahasiswa::where('matakuliah_kelasid', $valueMatakuliah_Kelas->id)->get();
                            foreach ($nilaiAkhirMahasiswa as $valueNilaiAkhir) {
                                $update_nilai_akhir = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
                                    ->join('soal', 'soal.id', 'soal_sub_cpmk.soal_id')
                                    ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
                                    ->where('nilai_mahasiswa.matakuliah_kelasid', $valueNilaiAkhir->matakuliah_kelasid)
                                    ->where('nilai_mahasiswa.mahasiswa_id', $valueNilaiAkhir->mahasiswa_id)
                                    ->selectRaw('(SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / 100) AS nilai_akhir')
                                    ->first();

                                // dd($update_nilai_akhir);
                                // dd('dont cont.');
                                $valueNilaiAkhir->nilai_akhir = $update_nilai_akhir->nilai_akhir == null ? 0 : $update_nilai_akhir->nilai_akhir;
                                $valueNilaiAkhir->save();
                            }
                        }
                    }
                    SoalSubCpmk::where('subcpmk_id', $valueSubCpmk->id)->delete();
                }
                SubCpmk::where('cpmk_id', $id)->delete();
            }
            Cpmk::where('cpl_id', $id)->delete();
            Cpl::where('id', $id)->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
        }
    }

    public function downloadExcel()
    {
        return Excel::download(new CplFormatExcel(), 'cpl-excel.xlsx');
    }

    // public function importExcel(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls'
    //     ]);

    //     $file = $request->file('file');


    //     Excel::import(new DosenImportExcel(), $file);

    //     return redirect()->back()->with('success', 'Data imported successfully.');
    // }
}
