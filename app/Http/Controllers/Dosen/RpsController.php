<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Cpl;
use App\Models\Rps;
use App\Models\Cpmk;
use App\Models\Soal;
use App\Models\SubCpmk;
use App\Exports\RpsExcel;
use App\Imports\RpsImport;
use App\Models\MataKuliah;
use App\Models\KelasKuliah;
use App\Models\SoalSubCpmk;
use Illuminate\Http\Request;
use App\Models\NilaiMahasiswa;
use App\Models\NilaiAkhirMahasiswa;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class RpsController extends Controller
{

    // public function create($id)
    // {
    //     $matkul= MataKuliah::find($id);
    //     $cpl= Cpl::pluck('kode_cpl', 'id');
    //     $kode_cpmk = Cpmk::where('matakuliah_id', '=', $id)->pluck('kode_cpmk', 'id');
    //     $kode_subcpmk = SubCpmk::join('cpmk', 'sub_cpmk.cpmk_id', '=', 'cpmk.id')
    //         ->where('cpmk.matakuliah_id', $id)
    //         ->pluck('sub_cpmk.kode_subcpmk', 'sub_cpmk.id');
    //         // dd ($kode_subcpmk);
    //     $data_cpmk =Cpmk::join('cpl', 'cpmk.cpl_id', 'cpl.id')
    //     ->where('matakuliah_id', '=', $id)
    //     ->select('cpmk.kode_cpmk', 'cpl.kode_cpl', 'cpmk.id', 'cpmk.deskripsi')
    //     ->orderBy('cpmk.id', 'desc')
    //     ->paginate(10);
    //     $start_nocpmk = ($data_cpmk->currentPage() - 1) * $data_cpmk->perPage() + 1;
    //     // $data_subcpmk = SubCpmk::where('cpmk_id', '=', $id)->paginate(5);
    //     $data_subcpmk = SubCpmk::join('cpmk', 'sub_cpmk.cpmk_id', '=', 'cpmk.id')
    //         ->where('cpmk.matakuliah_id', $id)
    //         ->select('cpmk.kode_cpmk', 'sub_cpmk.kode_subcpmk', 'sub_cpmk.id', 'sub_cpmk.deskripsi')
    //         ->orderBy('sub_cpmk.id', 'desc')
    //         ->paginate(10);
    //     $start_nosubcpmk = ($data_subcpmk->currentPage() - 1) * $data_subcpmk->perPage() + 1;
    //     $data_soalsubcpmk = SoalSubCpmk::join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
    //         ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
    //         ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
    //         ->where('cpmk.matakuliah_id', $id)
    //         ->select('soal_sub_cpmk.id', 'sub_cpmk.kode_subcpmk', 'soal.bentuk_soal', 'soal_sub_cpmk.bobot_soal', 'soal_sub_cpmk.waktu_pelaksanaan')
    //         ->orderBy('soal_sub_cpmk.id', 'desc')
    //         ->paginate(10);
    //         // dd($data_soalsubcpmk);

    //     $start_nosoalsubcpmk = ($data_soalsubcpmk->currentPage() - 1) * $data_soalsubcpmk->perPage() + 1;
    //     // dd($data_cpmk);
    //     $data_soal = Soal::pluck('bentuk_soal', 'id');

    //         // Mendapatkan langkah saat ini dari sesi, jika tidak ada maka default ke langkah pertama
    // $currentStep = session('currentStep', 0);

    // // Menyimpan langkah saat ini ke sesi
    // session(['currentStep' => $currentStep]);

    //     return view('pages-dosen.mata_kuliah.tambah_rps', compact('currentStep','cpl','matkul','kode_cpmk','kode_subcpmk', 'data_cpmk', 'start_nocpmk', 'data_subcpmk', 'start_nosubcpmk', 'data_soalsubcpmk', 'start_nosoalsubcpmk', 'data_soal'));
    // }
    public function create($id)
    {
        $rps= Rps::join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
            ->where('rps.id', $id)
            ->select('rps.*', 'mata_kuliah.nama_matkul')
            ->first();
        $cpl= Cpl::pluck('kode_cpl', 'id');
        $kode_cpmk = Cpmk::where('rps_id', '=', $id)->pluck('kode_cpmk', 'id');
        $kode_subcpmk = SubCpmk::join('cpmk', 'sub_cpmk.cpmk_id', '=', 'cpmk.id')
            ->where('cpmk.rps_id', $id)
            ->pluck('sub_cpmk.kode_subcpmk', 'sub_cpmk.id');
            // dd ($kode_subcpmk);
        // $data_subcpmk = SubCpmk::where('cpmk_id', '=', $id)->paginate(5);
        // dd($data_cpmk);
        $data_soal = Soal::pluck('bentuk_soal', 'id');
        return view('pages-dosen.mata_kuliah.tambah_rps', compact('cpl','rps', 'data_soal'));
    }

    public function listKodeCpmk($id)
    {
        $data['kode_cpmk'] = Cpmk::where('rps_id', '=', $id)->pluck('kode_cpmk', 'id');
        return view('pages-dosen.mata_kuliah.partials.input.list_cpmk', $data);
    }

    public function listKodeSubCpmk($id)
    {
        $data['kode_subcpmk'] = SubCpmk::join('cpmk', 'sub_cpmk.cpmk_id', '=', 'cpmk.id')
            ->where('cpmk.rps_id', $id)
            ->pluck('sub_cpmk.kode_subcpmk', 'sub_cpmk.id');
        return view('pages-dosen.mata_kuliah.partials.input.list_subcpmk', $data);
    }

    public function storecpmk(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'cpl_id' => 'required|exists:cpl,id',
            'kode_cpmk' => 'required|string',
            'deskripsi_cpmk' => 'required|string',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
        }

        try {
            $id_rps = $id;
            Cpmk::create([
                'rps_id' => $id_rps,
                'cpl_id' => $request->cpl_id,
                'kode_cpmk' => $request->kode_cpmk,
                'deskripsi' => $request->deskripsi_cpmk,
            ]);

            // return response()->json(['success' => true, 'message' => 'Data berhasil ditambahkan']);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
            // return redirect()->route('admin.rps.create', $id)->with('success', 'Data Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal ditambahkan: ' . $e->getMessage()], 500);
        }
    }

    public function storesubcpmk(Request $request, $id) {
        $validate = Validator::make($request->all(), [
            'pilih_cpmk' => 'required|exists:cpmk,id',
            'kode_subcpmk' => 'required|string',
            'deskripsi_subcpmk' => 'required|string',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
        }

        try {
            SubCpmk::create([
                'cpmk_id' => $request->pilih_cpmk,
                'kode_subcpmk' => $request->kode_subcpmk,
                'deskripsi' => $request->deskripsi_subcpmk,
            ]);

            // return response()->json(['success' => true, 'message' => 'Data berhasil ditambahkan']);
            // return redirect()->route('admin.rps.create', $id)->with('success', 'Data Berhasil Ditambahkan');
            return response()->json(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal ditambahkan: ' . $e->getMessage()], 500);
            // return redirect()->back()->withErrors(['errors' => 'Data Gagal Ditambahkan: '.$e->getMessage()])->withInput();
        }

    }

    public function listSubCpmk($id) {
        $data['data_subcpmk'] = SubCpmk::join('cpmk', 'sub_cpmk.cpmk_id', '=', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
            ->where('cpmk.rps_id', $id)
            ->select('cpl.kode_cpl', 'cpmk.kode_cpmk', 'sub_cpmk.kode_subcpmk', 'sub_cpmk.id', 'sub_cpmk.deskripsi')
            ->paginate(20);
        $data['start_nosubcpmk'] = ($data['data_subcpmk']->currentPage() - 1) * $data['data_subcpmk']->perPage() + 1;


        return view('pages-dosen.mata_kuliah.partials.tabel_rps.tabel_subcpmk', $data);
    }

    public function listCpmk($id) {
        $data['data_cpmk'] =Cpmk::join('cpl', 'cpmk.cpl_id', 'cpl.id')
        ->where('rps_id', '=', $id)
        ->select('cpmk.kode_cpmk', 'cpl.kode_cpl', 'cpmk.id', 'cpmk.deskripsi')
        ->paginate(20);
        $data['start_nocpmk'] = ($data['data_cpmk']->currentPage() - 1) * $data['data_cpmk']->perPage() + 1;

        return view('pages-dosen.mata_kuliah.partials.tabel_rps.tabel_cpmk', $data);
    }

    public function listTugas($id) {
        $data['data_soalsubcpmk'] = SoalSubCpmk::join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
            ->where('cpmk.rps_id', $id)
            ->select('cpl.kode_cpl', 'cpmk.kode_cpmk', 'soal_sub_cpmk.id', 'sub_cpmk.kode_subcpmk', 'soal.bentuk_soal', 'soal_sub_cpmk.bobot_soal', 'soal_sub_cpmk.waktu_pelaksanaan')
            ->paginate(20);
            // ->toSql();
            // dd($data_soalsubcpmk);

        $data['start_nosoalsubcpmk'] = ($data['data_soalsubcpmk']->currentPage() - 1) * $data['data_soalsubcpmk']->perPage() + 1;

        $total_bobot = SoalSubCpmk::join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
        ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
        ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
        ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
        ->where('cpmk.rps_id', $id)
        ->sum('soal_sub_cpmk.bobot_soal');

        // Membulatkan nilai total bobot
        $data['total_bobot_rps'] = ceil($total_bobot);

        return view('pages-dosen.mata_kuliah.partials.tabel_rps.tabel_tugas', $data);
    }

    public function storesoal(Request $request, $id) {
        $validate = Validator::make($request->all(), [
            // 'pilih_subcpmk' => 'required|exists:sub_cpmk,id',
            'pilih_subcpmk' => 'required|array',
            'pilih_subcpmk.*' => 'required|exists:sub_cpmk,id',
            'bobot' => 'required',
            'waktu_pelaksanaan_start' => 'required|numeric|min:1|max:17',
            'waktu_pelaksanaan_end' => 'required|numeric|min:1|max:17',
            // 'waktu_pelaksanaan' => 'required',
            'bentuk_soal' => 'required',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
        }

        try {
            $bentukSoal = request()->input('bentuk_soal');
            $existingUnit = Soal::where('bentuk_soal', $bentukSoal)->first();
            if (!$existingUnit) {
                $soal = new Soal();
                $soal->bentuk_soal = $bentukSoal;
                $soal->save();
            } else {
                $soal = $existingUnit;
            }

            if ($request->waktu_pelaksanaan_start == $request->waktu_pelaksanaan_end) {
                $minggu = "Minggu " . $request->waktu_pelaksanaan_start;
            } else {
                $minggu = "Minggu " . $request->waktu_pelaksanaan_start . " - " . $request->waktu_pelaksanaan_end;
            }
            // dd($bentukSoal);
            // Membuat dan menyimpan data ke dalam tabel SoalSubCpmk
            // $soalSubCpmkData = $request->except('soal_id');
            foreach ($request->pilih_subcpmk as $subcpmkid) {
                SoalSubCpmk::create([
                    'subcpmk_id' => $subcpmkid,
                    'bobot_soal' => $request->bobot,
                    'waktu_pelaksanaan' => $minggu,
                    'soal_id' => $soal->id
                ]);
            }

            // Kembalikan respons dengan berhasil
            return response()->json(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
            // return redirect()->route('admin.rps.create', $id)->with('success', 'Data Berhasil Ditambahkan');
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            return response()->json(['status' => 'error', 'message' => 'Data gagal ditambahkan: ' . $e->getMessage()], 500);
            // return redirect()->back()->withErrors(['errors' => 'Data Gagal Ditambahkan: '.$e->getMessage()])->withInput();
        }
    }

    public function export($id)
    {
        $rps = Rps::join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
        ->select(
            'rps.tahun_rps',
            'mata_kuliah.nama_matkul',
        )
        ->where('rps.id', $id)
        ->first();

        return Excel::download(new RpsExcel($id), 'Data RPS '. $rps->nama_matkul.' ('.$rps->tahun_rps.').xlsx');
    }

    public function import(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('file');


        Excel::import(new RpsImport($id), $file);

        return response()->json(['status' => 'success', 'message' => 'Data berhasil diimpor']);
    }

    public function destroyCpmk($id)
    {
        try {
            $subcpmk = SubCpmk::where('cpmk_id', $id)->select('id')->get();
                foreach ($subcpmk as $valueSubCpmk) {
                    $soalsubcpmk = SoalSubCpmk::where('subcpmk_id', $valueSubCpmk->id)->select('id')->get();
                    foreach ($soalsubcpmk as $valueSoal) {
                        NilaiMahasiswa::where('soal_id', $valueSoal->id)->delete();
                        $rps = CPMK::where('id', $id)->select('rps_id')->first();
                        // dd($rps_id);
                        $matakuliah_kelas = KelasKuliah::where('rps_id', $rps->rps_id)->select('id')->get();
                        // dd($matakuliah_kelas);
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
            $cpmk = Cpmk::findOrFail($id);
            // $cpmk->sub_cpmk()->delete();
            $cpmk->delete();
            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
        }
    }

    public function destroySubCpmk($id)
    {
        try {
            $soalsubcpmk = SoalSubCpmk::where('subcpmk_id', $id)->select('id')->get();
            foreach ($soalsubcpmk as $valueSoal) {
                NilaiMahasiswa::where('soal_id', $valueSoal->id)->delete();
                $cpmk = SubCpmk::where('id', $id)->first();
                $rps = CPMK::where('id', $cpmk->cpmk_id)->select('rps_id')->first();
                // dd($rps->rps_id);
                $matakuliah_kelas = KelasKuliah::where('rps_id', $rps->rps_id)->select('id')->get();
                // dd($matakuliah_kelas);
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
            SoalSubCpmk::where('subcpmk_id', $id)->delete();
            $subcpmk = SubCpmk::findOrFail($id);
            $subcpmk->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
        }
    }

    public function destroySoal($id)
    {
        try {
            NilaiMahasiswa::where('soal_id', $id)->delete();
            $cpmk = SoalSubCpmk::join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->select('sub_cpmk.cpmk_id')
            ->where('soal_sub_cpmk.id', $id)->first();
            $rps = CPMK::where('id', $cpmk->cpmk_id)->select('rps_id')->first();
            // dd($rps->rps_id);
            $matakuliah_kelas = KelasKuliah::where('rps_id', $rps->rps_id)->select('id')->get();
            // dd($matakuliah_kelas);
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
            $soal = SoalSubCpmk::findOrFail($id);
            $soal->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
        }
    }

    public function editCpmk($id)
    {
        try{
            $cpmk = Cpmk::join('cpl', 'cpmk.cpl_id', '=', 'cpl.id')
                    ->where('cpmk.id', $id)
                    ->select('cpmk.*', 'cpl.id as cpl_id', 'cpl.kode_cpl') // Sesuaikan dengan kolom-kolom yang Anda butuhkan dari tabel auth
                    ->first();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus','data' => $cpmk]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
        }
    }

    public function updateCpmk(Request $request)
    {
        try{
            // dd($request->all());
            // Update data produk berdasarkan ID
            $cpmk = Cpmk::where('id', $request->cpmk_id)->first();

            $cpmk->update([
                'cpl_id' => $request->cpl_id,
                'kode_cpmk' => $request->kode_cpmk,
                'deskripsi' => $request->deskripsi_cpmk,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate','data' => $cpmk]);
        } catch (\Exception $e) {
            // dd($e->getMessage(), $e->getTrace()); // Tambahkan ini untuk melihat pesan kesalahan
            return response()->json(['status' => 'error', 'message' => 'Data gagal diupdate: ' . $e->getMessage()], 500);
        }
    }

    public function editSubCpmk($id)
    {
        try{
            $subcpmk = SubCpmk::join('cpmk', 'sub_cpmk.cpmk_id', '=', 'cpmk.id')
                    ->where('sub_cpmk.id', $id)
                    ->select('sub_cpmk.*', 'cpmk.id as cpmk_id') // Sesuaikan dengan kolom-kolom yang Anda butuhkan dari tabel auth
                    ->first();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus','data' => $subcpmk]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
        }
    }

    public function updateSubCpmk(Request $request)
    {
        try{
            // Update data produk berdasarkan ID
            $subcpmk = SubCpmk::where('id', $request->subcpmk_id)->first();

            $subcpmk->update([
                'cpmk_id' => $request->pilih_cpmk,
                'kode_subcpmk' => $request->kode_subcpmk,
                'deskripsi' => $request->deskripsi_subcpmk,
            ]);

           return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate','data' => $subcpmk]);
        } catch (\Exception $e) {
            // dd($e->getMessage(), $e->getTrace()); // Tambahkan ini untuk melihat pesan kesalahan
            return response()->json(['status' => 'error', 'message' => 'Data gagal diupdate: ' . $e->getMessage()], 500);
        }
    }

    public function editSoalSubCpmk($id)
    {
        try{
            $soalsubcpmk = SoalSubCpmk::join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
                ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
                ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
                ->where('soal_sub_cpmk.id', $id)
                ->select('sub_cpmk.id as subcpmk_id', 'soal_sub_cpmk.id', 'sub_cpmk.kode_subcpmk', 'soal.bentuk_soal', 'soal_sub_cpmk.bobot_soal', 'soal_sub_cpmk.waktu_pelaksanaan') // Sesuaikan dengan kolom-kolom yang Anda butuhkan dari tabel auth
                ->first();
            // dd($soalsubcpmk);
            $minggu = $soalsubcpmk->waktu_pelaksanaan;

            // Menggunakan regex untuk menemukan angka
            preg_match_all('/\d+/', $minggu, $matches);

            $angka = collect($matches[0]);

            $waktu_pelaksanaan_start = $angka->first();
            $waktu_pelaksanaan_end = $angka->last();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus','data' => $soalsubcpmk, 'minggu_mulai' => $waktu_pelaksanaan_start,
                'minggu_akhir' => $waktu_pelaksanaan_end,]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
        }
    }

    public function updateSoalSubCpmk(Request $request)
    {
        try{
            $bentukSoal = request()->input('bentuk_soal');
            $existingUnit = Soal::where('bentuk_soal', $bentukSoal)->first();
            if (!$existingUnit) {
                $soal = new Soal();
                $soal->bentuk_soal = $bentukSoal;
                $soal->save();
            } else {
                $soal = $existingUnit;
            }

            if ($request->waktu_pelaksanaan_start == $request->waktu_pelaksanaan_end) {
                $minggu = "Minggu " . $request->waktu_pelaksanaan_start;
            } else {
                $minggu = "Minggu " . $request->waktu_pelaksanaan_start . " - " . $request->waktu_pelaksanaan_end;
            }

            $subcpmkId = is_array($request->pilih_subcpmk) ? $request->pilih_subcpmk[0] : $request->pilih_subcpmk;

            $soalsubcpmk = SoalSubCpmk::where('id', $request->soal_subcpmk_id)->first();

            $soalsubcpmk->update([
                'subcpmk_id' => $subcpmkId,
                'soal_id' => $soal->id,
                'bobot_soal' => $request->bobot,
                'waktu_pelaksanaan' =>  $minggu,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate','data' => $soalsubcpmk]);
        } catch (\Exception $e) {
            // dd($e->getMessage(), $e->getTrace()); // Tambahkan ini untuk melihat pesan kesalahan
            return response()->json(['status' => 'error', 'message' => 'Data gagal diupdate: ' . $e->getMessage()], 500);
        }
    }
}
