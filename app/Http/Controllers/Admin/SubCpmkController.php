<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cpmk;
use App\Models\SubCpmk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCpmkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SubCpmk::join('cpmk', 'sub_cpmk.cpmk_id', '=', 'cpmk.id')
        ->join('mata_kuliah', 'cpmk.matakuliah_id', '=', 'mata_kuliah.id')
        ->select('sub_cpmk.*', 'cpmk.kode_cpmk as cpmk', 'mata_kuliah.nama_matkul as nama_matkul');

        // Cek apakah ada parameter pencarian
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('sub_cpmk.kode_subcpmk', 'like', '%' . $searchTerm . '%')
                    ->orWhere('cpmk.kode_cpmk', 'like', '%' . $searchTerm . '%')
                    ->orWhere('mata_kuliah.nama_matkul', 'like', '%' . $searchTerm . '%');
            });
        }

        $sub_cpmk = $query->paginate(10);

        $startNumber = ($sub_cpmk->currentPage() - 1) * $sub_cpmk->perPage() + 1;

        return view('pages-admin.sub_cpmk.sub_cpmk', [
            'data' => $sub_cpmk,
            'startNumber' => $startNumber,
        ])->with('success', 'Data Sub CPMK Ditemukan');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cpmk = Cpmk::join('mata_kuliah', 'mata_kuliah.id', '=', 'cpmk.matakuliah_id')
        ->select('cpmk.id', 'cpmk.kode_cpmk', 'mata_kuliah.nama_matkul')
        ->get();
        $kode_cpmk = Cpmk::pluck('kode_cpmk', 'id');

        return view('pages-admin.sub_cpmk.tambah_sub_cpmk', compact('cpmk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'cpmk' => 'required|exists:cpmk,id',
            'kode_subcpmk' => 'required|string',
            'deskripsi' => 'nullable',
            'bentuk_soal' => 'required',
            'bobot_subcpmk' => 'required',
            'waktu_pelaksanaan' => 'required',
        ]);

        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }

        try {
            SubCpmk::create([
                'cpmk_id' => $request->cpmk,
                'kode_subcpmk' => $request->kode_subcpmk,
                'deskripsi' => $request->deskripsi,
                'bentuk_soal' => $request->bentuk_soal,
                'bobot_subcpmk' => $request->bobot_subcpmk,
                'waktu_pelaksanaan' => $request->waktu_pelaksanaan,
            ]);

            return redirect()->route('admin.subcpmk')->with('success', 'Data Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['errors' => 'Data Gagal Ditambahkan: '.$e->getMessage()])->withInput();
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
    public function edit(string $id)
    {
        $subcpmk = SubCpmk::find($id);

        $cpmk = Cpmk::join('mata_kuliah', 'mata_kuliah.id', '=', 'cpmk.matakuliah_id')
        ->select('cpmk.id', 'cpmk.kode_cpmk', 'mata_kuliah.nama_matkul')
        ->get();

        return view('pages-admin.sub_cpmk.edit_sub_cpmk', [
            'success' => 'Data Ditemukan',
            'data' => $subcpmk,
            'cpmk' => $cpmk,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'cpmk' => 'required|exists:cpmk,id',
            'kode_subcpmk' => 'required|string',
            'deskripsi' => 'nullable',
            'bentuk_soal' => 'required',
            'bobot_subcpmk' => 'required',
            'waktu_pelaksanaan' => 'required',
        ]);

        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }

        try {
            $subcpmk = SubCpmk::find($id);
            $subcpmk->update([
                'cpmk_id' => $request->cpmk,
                'kode_subcpmk' => $request->kode_subcpmk,
                'deskripsi' => $request->deskripsi,
                'bentuk_soal' => $request->bentuk_soal,
                'bobot_subcpmk' => $request->bobot_subcpmk,
                'waktu_pelaksanaan' => $request->waktu_pelaksanaan,
            ]);

            return redirect()->route('admin.subcpmk')->with([
                'success' => 'Data Berhasil Diupdate',
                'data' => $subcpmk
            ]);
        } catch (\Exception $e) {
            // dd($e->getMessage(), $e->getTrace()); // Tambahkan ini untuk melihat pesan kesalahan
            return redirect()->route('admin.subcpmk.edit', $id)->with('error', 'Data Gagal Diupdate: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            SubCpmk::where('id', $id)->delete();

            return redirect()->route('admin.subcpmk')
                ->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.subcpmk')
                ->with('error', 'Data gagal dihapus: ' . $e->getMessage());
        }
    }
}
