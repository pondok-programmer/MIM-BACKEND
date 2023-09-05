<?php

namespace App\Http\Controllers\Profil;

use Carbon\Carbon;
use App\Models\User;
use Hashids\Hashids;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProfilController extends Controller
{
    public function showProfil(){
        $users = auth()->user();
        $user = $users->id;

        $hashids = new Hashids('your-secret-salt', 10); // sesuaikan dengan konfigurasi Anda
        $hashedId = $hashids->encode($user);

        $profil = User::where('id', $user)->first();

        $responseUser = $profil->toArray();
        $responseUser['id'] = $hashedId;
        return response()->json([
            'Profil' => $responseUser
        ]);
    }

    public function updateProfil(Request $request, $id){
        $hashids = new Hashids('your-secret-salt', 10);
        $user = User::find($hashids->decode($id)[0]);

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

    
    
    public function showAllUser(){
        $users = User::where('role', 'user')->get();
        $hashids = new Hashids('your-secret-salt', 10);

        $usersArray = $users->map(function($user) use ($hashids) {
            $array = $user->toArray();
            $encodedId = $hashids->encode($user->id);
            if ($encodedId) {
                $array['id'] = $encodedId;
            } else {
                return response()->json([
                    'Data User' => 'Id Tidak Bisa DIHash'
                ]);    
            }
            return $array;
        })->toArray();

        return response()->json([
            'DataUser' => $usersArray
        ]);
    }

    public function showOneUser($id){
        $hashids = new Hashids('your-secret-salt', 10);
        $userShow = User::where('id', $hashids->decode($id))->first();

        if(!$userShow){
            return response()->json([
                'Data User' => 'Not Found'
            ],404);
        }
        $responseUser = $userShow->toArray();
        $encodedId = $hashids->encode($userShow->id);
        if ($encodedId) {
            $responseUser['id'] = $encodedId ;
        } else {
            return response()->json([
                'Data User' => 'Id Tidak Bisa DIHash'
            ]);    
        }
        

        
        return response()->json([
            'Profil' => $responseUser
        ]);
    }
}
