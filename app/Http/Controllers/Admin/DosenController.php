<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Dosen;
use App\Models\KelasKuliah;
use Illuminate\Http\Request;
use App\Models\NilaiMahasiswa;
use App\Exports\DosenFormatExcel;
use App\Imports\DosenImportExcel;
use App\Models\NilaiAkhirMahasiswa;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Dosen::join('auth', 'dosen.id_auth', '=', 'auth.id')
            ->select('dosen.*', 'auth.username as username');

        // Cek apakah ada parameter pencarian
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('dosen.nama', 'like', '%' . $searchTerm . '%')
                    ->orWhere('dosen.nidn', 'like', '%' . $searchTerm . '%');
            });
        }

        $dosen = $query->paginate(20);

        $startNumber = ($dosen->currentPage() - 1) * $dosen->perPage() + 1;

        return view('pages-admin.dosen.dosen', [
            'data' => $dosen,
            'startNumber' => $startNumber,
        ])->with('success', 'Data Dosen Ditemukan');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages-admin.dosen.tambah_dosen');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama' => 'required|string',
            'email' => 'required|email',
            'nidn' => 'required|unique:dosen,nidn',
            'telp' => 'required|string|unique:dosen,telp',
            'status' => 'required',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
        }

        try {
            $image = null;

            // Jika ada file gambar yang diunggah, proses upload
            if ($request->hasFile('image')) {
                $image = time() . '_' . $request->file('image')->getClientOriginalName();

                while (Storage::exists('public/image/' . $image)) {
                    $image = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
                }

                $request->file('image')->storeAs('public/image', $image);
            }

            $password = 'tekkim123';
            $register = User::create([
                'username' => $request->email,
                'password' => Hash::make($password),
                'role' => 'dosen',
            ]);

            $id_auth = $register->id;
            // dd($request->email);
            Dosen::create([
                'id_auth' => $id_auth,
                'nama' => $request->nama,
                'nidn' => $request->nidn,
                'telp' => $request->telp,
                'email' => $request->email,
                'status' => $request->status,
                'image' => $image
            ]);

            // return redirect()->route('admin.dosen')->with('success', 'Data Dosen Berhasil Ditambahkan');
            return response()->json(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal ditambahkan: ' . $e->getMessage()], 500);
            // return redirect()->back()->withErrors(['errors' => 'Data Gagal Ditambahkan: ' . $e->getMessage()])->withInput();
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
        $dosen = Dosen::join('auth', 'dosen.id_auth', '=', 'auth.id')
            ->where('dosen.id', $id)
            ->select('dosen.*', 'auth.username') // Sesuaikan dengan kolom-kolom yang Anda butuhkan dari tabel auth
            ->first();

        return view('pages-admin.dosen.edit_dosen', [
            'success' => 'Data Ditemukan',
            'data' => $dosen,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'password' => 'nullable|min:8',
            'nama' => 'required|string',
            'nidn' => 'required',
            'telp' => 'required|string',
            'status' => 'required',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
            // return redirect()->back()->withErrors($validate)->withInput();
        }

        try {
            $image = null;

            // Jika ada file gambar yang diunggah, proses upload
            if ($request->hasFile('image')) {
                $image = time() . '_' . $request->file('image')->getClientOriginalName();

                while (Storage::exists('public/image/' . $image)) {
                    $image = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
                }

                $request->file('image')->storeAs('public/image', $image);
            }

            // Update data produk berdasarkan ID
            $dosen = Dosen::where('id', $id)->first();
            $password = $request->password;

            if ($password) {
                $user = User::Where('id_auth', $dosen->id_auth)->first();

                $user->update([
                    'password' => $password ? Hash::make($password) : $user->password,
                ]);
            }

            $dosen->update([
                'nama' => $request->nama,
                'nidn' => $request->nidn,
                'telp' => $request->telp,
                'status' => $request->status,
                'image' => $image ? $image : $dosen->image,
            ]);

            // return redirect()->route('admin.dosen')->with([
            //     'success' => 'Data Berhasil diupdate',
            //     'data' => $dosen
            // ]);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate', 'data' => $dosen]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal diupdate: ' . $e->getMessage()], 500);
            // dd($e->getMessage(), $e->getTrace()); // Tambahkan ini untuk melihat pesan kesalahan
            // return redirect()->route('admin.dosen.edit', $id)->with('error', 'Data Gagal Diupdate: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $dosen = Dosen::where('id_auth', $id)->select('id')->first();
            $matakuliah_kelas = KelasKuliah::where('dosen_id', $dosen->id)->get();
            foreach ($matakuliah_kelas as $valueMatakuliah_Kelas) {
                NilaiMahasiswa::where('matakuliah_kelasid', $valueMatakuliah_Kelas->id)->delete();
                NilaiAkhirMahasiswa::where('matakuliah_kelasid', $valueMatakuliah_Kelas->id)->delete();
            }
            KelasKuliah::where('dosen_id', $dosen->id)->delete();
            Dosen::where('id_auth', $id)->delete();
            User::where('id', $id)->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
        }
    }

    public function deleteMultiple(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'] ?? null;

        if (is_array($ids) && !empty($ids)) {
            try {
                foreach ($ids as $id) {
                    $dosen = Dosen::where('id_auth', $id)->select('id')->first();

                    if ($dosen) {
                        $matakuliah_kelas = KelasKuliah::where('dosen_id', $dosen->id)->get();
                        foreach ($matakuliah_kelas as $valueMatakuliah_Kelas) {
                            NilaiMahasiswa::where('matakuliah_kelasid', $valueMatakuliah_Kelas->id)->delete();
                            NilaiAkhirMahasiswa::where('matakuliah_kelasid', $valueMatakuliah_Kelas->id)->delete();
                        }
                        KelasKuliah::where('dosen_id', $dosen->id)->delete();
                        Dosen::where('id_auth', $id)->delete();
                    }

                    User::where('id', $id)->delete();
                }
                return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus.']);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data.']);
        }
    }

    // public function deleteMultiple(Request $request)
    // {
    //     $data = json_decode($request->getContent(), true);
    //     // Log::info('Request Data:', $data);
    //     // $content = $request->getContent();
    //     // dd($content); // Pastikan ini berisi data JSON yang benar

    //     $ids = $data['ids'] ?? null;
    //     // dd($data);
    //     // Log::info('IDs received:', $ids);

    //     if (is_array($ids) && !empty($ids)) {
    //         Dosen::whereIn('id', $ids)->delete();
    //         return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus.']);
    //     } else {
    //         return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data.']);
    //     }
    // }

    public function downloadExcel()
    {
        return Excel::download(new DosenFormatExcel(), 'dosen-excel.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('file');

        Excel::import(new DosenImportExcel(), $file);

        // return redirect()->back()->with('success', 'Data imported successfully.');
        return response()->json(['status' => 'success', 'message' => 'Data berhasil diimpor']);
    }

    public function resetPassword(Request $request)
    {
        $id = $request->id;

        try {
            $dosen = Dosen::findOrFail($id);
            $auth = User::findOrFail($dosen->id_auth);
            $auth->password = Hash::make('tekkim123');
            $auth->save();

            return response()->json(['status' => 'success', 'message' => 'Berhasil reset password']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal reset password: ' . $e->getMessage()], 500);
        }
    }
}
