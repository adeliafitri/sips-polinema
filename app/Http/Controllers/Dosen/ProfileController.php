<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show($id) {
        // dd($id);
        $dosen = Dosen::join('auth', 'dosen.id_auth', '=', 'auth.id')
                    ->where('dosen.id_auth', $id)
                    ->select('dosen.*', 'auth.role') // Sesuaikan dengan kolom-kolom yang Anda butuhkan dari tabel auth
                    ->first();
        // dd($dosen);
        if (!$dosen) {
            return redirect()->route('pages-dosen.dosen.detail_user')->withErrors(['error' => 'dosen not found']);
        }
        return view('pages-dosen.dosen.detail_user', [
            'success' => 'Data Found',
            'data' => $dosen,
        ]);
    }

    public function edit($id) {
        // dd($id);
        $dosen = Dosen::join('auth', 'dosen.id_auth', '=', 'auth.id')
                    ->where('dosen.id_auth', $id)
                    ->select('dosen.*', 'auth.username') // Sesuaikan dengan kolom-kolom yang Anda butuhkan dari tabel auth
                    ->first();
        // dd($dosen);
        if (!$dosen) {
            return redirect()->route('pages-dosen.dosen.edit_user')->withErrors(['error' => 'dosen not found']);
        }
        return view('pages-dosen.dosen.edit_user', [
            'success' => 'Data Found',
            'data' => $dosen,
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
            $dosen = dosen::where('id_auth', $id)->first();
            $dosen->update([
                'nama' => $request->nama,
                'telp' => $request->telp,
                'image' => $image ? $image : $dosen->image,
            ]);
            session(['dosen' => $dosen]);
            //dd($dosen->getAttributes()); // Mengecek apakah atribut sudah di-update sesuai harapan

            // return redirect()->route('dosen.user', $id)->with([
            //     'success' => 'User updated successfully.',
            //     'data' => $dosen
            // ]);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate', 'data' => $dosen]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal diupdate: ' . $e->getMessage()], 500);
            // return redirect()->route('dosen.user.edit', $id)->with('error', 'Error updating user: ' . $e->getMessage())->withInput();
        }
    }

    public function showFormChangePass() {
        return view('pages-dosen.dosen.changePass');
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
