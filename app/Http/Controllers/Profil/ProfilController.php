<?php

namespace App\Http\Controllers\Profil;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProfilController extends Controller
{
    public function showProfil(){
        $users = auth()->user();
        $user = $users->id;

        $profil = User::where('id', $user)->get();
        return response()->json([
            'Profil' => $profil 
        ]);
    }

    public function updateProfil(Request $request, $id){

        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'jenkel' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_telp' => 'required|string|max:255|unique:users,no_telp,' . $id, //cara jika ingin mengupdate tapi membawa unique colomn
            'pendidikan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'range_gaji' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'jumlah_anak' => 'required|string|max:255',
            'img' => 'required|image|mimes:jpeg,png,jpg,svg,gif,webp|max:2000',
        ]);
        if ($validate->fails()) {
                return response()->json([
                    'error'=>true,
                    'message'=>$validate->errors()
                ]);
            }
            
            $user = User::find($id);

            if ($request->hasFile('img')) {
                // Menghapus foto lama jika ada
                if ($user->img) {
                    $publicId = pathinfo($user->img, PATHINFO_FILENAME);
                    Cloudinary::destroy($publicId);
                }
                // Upload img baru ke Cloudinary
                $uploadedImage = Cloudinary::upload($request->file('img')->getRealPath(), [
                    'folder' => 'MIM/ProfilMIM'
                ]);
                // Simpan URL img ke dalam database
                $user->img = $uploadedImage->getSecurePath();
            }

            $updateProfil = [
                'name' => $request->name, //full name
                'tgl_lahir' => Carbon::createFromFormat('l, d-F-Y', $request->tgl_lahir)->format('Y-m-d'),//l, d-F-Y(hari, tanggal-bulan-tahun)
                'tempat_lahir' => $request->tempat_lahir,
                'jenkel' => $request->jenkel,//Laki-laki Perempuan
                'alamat' => $request->alamat,//tulis dari user
                'no_telp' => $request->no_telp,// +62
                'pendidikan' => $request->pendidikan,//pendidikan terakhir
                'pekerjaan' => $request->pekerjaan,
                'range_gaji' => $request->range_gaji,
                'status' => $request->status,//status pernikahan sudah menika atau belum
                'jumlah_anak' => $request->jumlah_anak,
                'img' => $user->img,
            ];

            $user->update($updateProfil);

            return response()->json([
                'message' => 'Profil berhasil diupdate',
                'Data' => $updateProfil
            ]);
    }

    public function deleteAcc($id){

        User::destroy($id);
        
        return response()->json([
            'User' => 'Account has been delete'
        ]);
    }
}
