<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cpl;
use App\Models\Cpmk;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CpmkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Cpmk::join('cpl', 'cpmk.cpl_id', '=', 'cpl.id')
        ->join('mata_kuliah', 'cpmk.matakuliah_id', '=', 'mata_kuliah.id')
        ->select('cpmk.*', 'cpl.kode_cpl as cpl', 'mata_kuliah.nama_matkul as nama_matkul',
            DB::raw("(SELECT SUM(`s`.`bobot_subcpmk`) FROM `sub_cpmk` `s`
            INNER JOIN `cpmk` `k` ON `s`.`cpmk_id` = `k`.`id`
            WHERE `s`.`cpmk_id` = cpmk.id
            GROUP BY `s`.`cpmk_id`, `k`.`matakuliah_id`) as bobot_cpmk")
        );

        // Cek apakah ada parameter pencarian
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('cpmk.kode_cpmk', 'like', '%' . $searchTerm . '%')
                    ->orWhere('cpl.kode_cpl', 'like', '%' . $searchTerm . '%')
                    ->orWhere('mata_kuliah.nama_matkul', 'like', '%' . $searchTerm . '%');
            });
        }

        $cpmk = $query->paginate(5);

        $startNumber = ($cpmk->currentPage() - 1) * $cpmk->perPage() + 1;

        return view('pages-admin.cpmk.cpmk', [
            'data' => $cpmk,
            'startNumber' => $startNumber,
        ])->with('success', 'Data CPMK Ditemukan');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cpl = Cpl::pluck('kode_cpl', 'id');
        $mata_kuliah = MataKuliah::pluck('nama_matkul', 'id');
        return view('pages-admin.cpmk.tambah_cpmk', compact('cpl', 'mata_kuliah'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'cpl' => 'required|exists:cpl,id',
            'mata_kuliah' => 'required|exists:mata_kuliah,id',
            'kode_cpmk' => 'required|string',
            'deskripsi' => 'nullable',
        ]);

        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }

        try {
            Cpmk::create([
                'cpl_id' => $request->cpl,
                'matakuliah_id' => $request->mata_kuliah,
                'kode_cpmk' => $request->kode_cpmk,
                'deskripsi' => $request->deskripsi,
            ]);

            return redirect()->route('admin.cpmk')->with('success', 'Data Berhasil Ditambahkan');
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
    public function edit($id)
    {
        $cpmk = Cpmk::find($id);

        $cpl = Cpl::pluck('kode_cpl', 'id');
        $mata_kuliah = MataKuliah::pluck('nama_matkul', 'id');
        return view('pages-admin.cpmk.edit_cpmk', [
            'success' => 'Data Ditemukan',
            'data' => $cpmk,
            'cpl' => $cpl,
            'mata_kuliah' => $mata_kuliah
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'cpl' => 'required|exists:cpl,id',
            'mata_kuliah' => 'required|exists:mata_kuliah,id',
            'kode_cpmk' => 'required|string',
            'deskripsi' => 'nullable',
        ]);

        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }

        try {
            $cpmk = Cpmk::find($id);
            $cpmk->update([
                'cpl_id' => $request->cpl,
                'matakuliah_id' => $request->mata_kuliah,
                'kode_cpmk' => $request->kode_cpmk,
                'deskripsi' => $request->deskripsi,
            ]);

            return redirect()->route('admin.cpmk')->with([
                'success' => 'Data Berhasil Diupdate',
                'data' => $cpmk
            ]);
        } catch (\Exception $e) {
            // dd($e->getMessage(), $e->getTrace()); // Tambahkan ini untuk melihat pesan kesalahan
            return redirect()->route('admin.cpmk.edit', $id)->with('error', 'Data Gagal Diupdate: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Cpmk::where('id', $id)->delete();

            return redirect()->route('admin.cpmk')
                ->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.cpmk')
                ->with('error', 'Data gagal dihapus: ' . $e->getMessage());
        }
    }
}
