<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show($id) {
        // dd($id);
        $mahasiswa = Mahasiswa::join('auth', 'mahasiswa.id_auth', '=', 'auth.id')
                    ->where('mahasiswa.id_auth', $id)
                    ->select('mahasiswa.*', 'auth.role') // Sesuaikan dengan kolom-kolom yang Anda butuhkan dari tabel auth
                    ->first();
        // dd($mahasiswa);
        if (!$mahasiswa) {
            return redirect()->route('pages-mahasiswa.mahasiswa.detail_user')->withErrors(['error' => 'mahasiswa not found']);
        }
        return view('pages-mahasiswa.mahasiswa.detail_user', [
            'success' => 'Data Found',
            'data' => $mahasiswa,
        ]);
    }

    public function edit($id) {
        // dd($id);
        $mahasiswa = Mahasiswa::join('auth', 'mahasiswa.id_auth', '=', 'auth.id')
                    ->where('mahasiswa.id_auth', $id)
                    ->select('mahasiswa.*', 'auth.username') // Sesuaikan dengan kolom-kolom yang Anda butuhkan dari tabel auth
                    ->first();
        // dd($mahasiswa);
        if (!$mahasiswa) {
            return redirect()->route('pages-mahasiswa.mahasiswa.edit_user')->withErrors(['error' => 'mahasiswa not found']);
        }
        return view('pages-mahasiswa.mahasiswa.edit_user', [
            'success' => 'Data Found',
            'data' => $mahasiswa,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string',
            'telp' => 'required|string',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

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
            $mahasiswa = Mahasiswa::where('id_auth', $id)->first();
            $mahasiswa->update([
                'nama' => $request->nama,
                'telp' => $request->telp,
                'image' => $image ? $image : $mahasiswa->image,
            ]);
            session(['mahasiswa' => $mahasiswa]);
            //dd($mahasiswa->getAttributes()); // Mengecek apakah atribut sudah di-update sesuai harapan

            // return redirect()->route('mahasiswa.user', $id)->with([
            //     'success' => 'User updated successfully.',
            //     'data' => $mahasiswa
            // ]);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate', 'data' => $mahasiswa]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal diupdate: ' . $e->getMessage()], 500);
            // return redirect()->route('mahasiswa.user.edit', $id)->with('error', 'Error updating user: ' . $e->getMessage())->withInput();
        }
    }

    public function showFormChangePass() {
        return view('pages-mahasiswa.mahasiswa.changePass');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ], [
            'old_password.required' => 'Password lama harus diisi',
            'new_password.required' => 'Password baru harus diisi',
            // 'new_password.min' => 'The new password must be at least 8 characters.',
            'confirm_password.required' => 'Konfirmasi password harus diisi ',
            'confirm_password.same' => 'Konfirmasi password tidak sesuai dengan password baru',
        ]);

        $currentPasswordStatus = Hash::check($request->old_password, auth()->user()->password);
        if($currentPasswordStatus){

            User::findOrFail(Auth::user()->id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            // return redirect()->back()->with('success','Password Updated Successfully');
            return response()->json(['status' => 'success', 'message' => 'Password berhasil diupdate']);
        }else{
            return response()->json(['status' => 'error', 'message' => 'Gagal update: Password lama salah atau tidak sesuai']);
            // return redirect()->back()->with('error','Current Password does not match with Old Password');
        }
    }
}
