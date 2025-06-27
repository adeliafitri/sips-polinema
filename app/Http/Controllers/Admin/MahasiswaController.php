<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MahasiswaFormatExcel;
use App\Http\Controllers\Controller;
use App\Imports\MahasiswaImportExcel;
use App\Models\Mahasiswa;
use App\Models\NilaiAkhirMahasiswa;
use App\Models\NilaiMahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Mahasiswa::join('auth', 'mahasiswa.id_auth', '=', 'auth.id')
            ->select('mahasiswa.*', 'auth.username as username');

        // Cek apakah ada parameter pencarian
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('mahasiswa.nama', 'like', '%' . $searchTerm . '%')
                    ->orWhere('mahasiswa.nim', 'like', '%' . $searchTerm . '%');
            });
        }

        $mahasiswa = $query->orderBy('angkatan', 'DESC')->paginate(20);

        $startNumber = ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() + 1;

        return view('pages-admin.mahasiswa.mahasiswa', [
            'data' => $mahasiswa,
            'startNumber' => $startNumber,
        ])->with('success', 'Data Mahasiswa Ditemukan');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages-admin.mahasiswa.tambah_mahasiswa');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama' => 'required|string',
            'nim' => 'required|unique:mahasiswa,nim',
            'telp' => 'required|string|unique:mahasiswa,telp',
            'angkatan' => 'required|numeric',
            'program_studi' => 'required|string',
            'status' => 'required',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
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

            $angkatan = Carbon::createFromFormat('Y', $request->angkatan)->format('Y');
            $nim = ($request->nim);
            // $tanggalLahir = Carbon::createFromFormat('d/m/Y', $request->tanggal_lahir)->format('Y-m-d');
            $password = $nim;
            $register = User::create([
                'username' => $request->nim,
                'password' => Hash::make($password),
                'role' => 'mahasiswa',
            ]);

            $id_auth = $register->id;

            // if ($request->status == 'lulus') {
            //     $tahun_lulus = $request->tahun_lulus;
            // }

            Mahasiswa::create([
                'id_auth' => $id_auth,
                'nama' => $request->nama,
                'nim' => $request->nim,
                'telp' => $request->telp,
                'angkatan' => $angkatan,
                'program_studi' => $request->program_studi,
                'status' => $request->status,
                'tahun_lulus' => $request->status == 'lulus' ? $request->tahun_lulus : '0000',
                'image' => $image
            ]);

            // return redirect()->route('admin.mahasiswa')->with('success', 'Data Mahasiswa Berhasil Ditambahkan');
            return response()->json(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
        } catch (\Exception $e) {
            // dd($e->getMessage(), $e->getTrace());
            // return redirect()->back()->withErrors(['errors' => 'Data Gagal Ditambahkan: '.$e->getMessage()])->withInput();
            return response()->json(['status' => 'error', 'message' => 'Data gagal ditambahkan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $mahasiswa = Mahasiswa::join('auth', 'mahasiswa.id_auth', '=', 'auth.id')
            ->where('mahasiswa.id', $id)
            ->select('mahasiswa.*', 'auth.username') // Sesuaikan dengan kolom-kolom yang Anda butuhkan dari tabel auth
            ->first();

        return view('pages-admin.mahasiswa.detail_mahasiswa', [
            'success' => 'Data Ditemukan',
            'data' => $mahasiswa,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::join('auth', 'mahasiswa.id_auth', '=', 'auth.id')
            ->where('mahasiswa.id', $id)
            ->select('mahasiswa.*', 'auth.username') // Sesuaikan dengan kolom-kolom yang Anda butuhkan dari tabel auth
            ->first();

        return view('pages-admin.mahasiswa.edit_mahasiswa', [
            'success' => 'Data Ditemukan',
            'data' => $mahasiswa,
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
            'nim' => 'required',
            'angkatan' => 'required|numeric',
            'program_studi' => 'required|string',
            'telp' => 'required|string',
            'status' => 'required',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
            // return redirect()->back()->withErrors($validate)->withInput();
        }

        try {
            $angkatan = Carbon::createFromFormat('Y', $request->angkatan)->format('Y');
            // $tanggalLahir = Carbon::createFromFormat('d/m/Y', $request->tanggal_lahir)->format('Y-m-d');
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
            $mahasiswa = Mahasiswa::where('id', $id)->first();
            $password = $request->password;

            if ($password) {
                $user = User::Where('id_auth', $mahasiswa->id_auth)->first();

                $user->update([
                    'password' => $password ? Hash::make($password) : $user->password,
                ]);
            }

            // if ($request->status == 'lulus') {
            //     $tahun_lulus = $request->tahun_lulus;
            // }

            $mahasiswa->update([
                'nama' => $request->nama,
                'nim' => $request->nim,
                'telp' => $request->telp,
                'angkatan' => $angkatan,
                'program_studi' => $request->program_studi,
                'status' => $request->status,
                'tahun_lulus' => $request->status == 'lulus' ? $request->tahun_lulus : '0000',
                'image' => $image ? $image : $mahasiswa->image,
            ]);

            // return redirect()->route('admin.mahasiswa')->with([
            //     'success' => 'User updated successfully.',
            //     'data' => $mahasiswa
            // ]);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate', 'data' => $mahasiswa]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal diupdate: ' . $e->getMessage()], 500);
            // dd($e->getMessage(), $e->getTrace()); // Tambahkan ini untuk melihat pesan kesalahan
            // return redirect()->route('admin.mahasiswa.edit', $id)->with('error', 'Data Gagal Diupdate: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $mahasiswa = Mahasiswa::where('id_auth', $id)->first();
            if ($mahasiswa) {
                $id_mahasiswa = $mahasiswa->id;
                NilaiAkhirMahasiswa::where('mahasiswa_id', $id_mahasiswa)->delete();
                NilaiMahasiswa::where('mahasiswa_id', $id_mahasiswa)->delete();
                $mahasiswa->delete();
                User::where('id', $id)->delete();
                return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
            }
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
                    $mahasiswa = Mahasiswa::where('id_auth', $id)->select('id')->first();
                    if ($mahasiswa) {
                        $id_mahasiswa = $mahasiswa->id;
                        NilaiAkhirMahasiswa::where('mahasiswa_id', $id_mahasiswa)->delete();
                        NilaiMahasiswa::where('mahasiswa_id', $id_mahasiswa)->delete();
                        $mahasiswa->delete();
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

    public function downloadExcel()
    {
        return Excel::download(new MahasiswaFormatExcel(), 'mahasiswa-excel.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('file');


        Excel::import(new MahasiswaImportExcel(), $file);

        return response()->json(['status' => 'success', 'message' => 'Data berhasil diimpor']);
    }

    public function resetPassword(Request $request)
    {
        $id = $request->id;

        try {
            $mahasiswa = Mahasiswa::findOrFail($id);
            $auth = User::findOrFail($mahasiswa->id_auth);
            $auth->password = Hash::make($mahasiswa->nim);
            $auth->save();

            return response()->json(['status' => 'success', 'message' => 'Berhasil reset password']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal reset password: ' . $e->getMessage()], 500);
        }
    }
}
