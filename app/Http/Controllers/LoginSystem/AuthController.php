<?php

namespace App\Http\Controllers\LoginSystem;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\SendEmailVerifyJob;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'jenkel' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_telp' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'pendidikan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'range_gaji' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'jumlah_anak' => 'required|string|max:255',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validate->fails()) {
                return response()->json([
                    'error'=>true,
                    'message'=>$validate->errors()
                ]);
            }

            // $uploadedImage = Cloudinary::upload($request->file('img')->getRealPath(), [
            //     'folder' => 'MIM/ProfilMIM'
            // ]);
                $users = User::create([
                    'name' => $request->name, //full name
                    'tgl_lahir' => $request->tgl_lahir,
                    'tempat_lahir' => $request->tempat_lahir,
                    'jenkel' => $request->jenkel,//Laki-laki Perempuan
                    'alamat' => $request->alamat,//tulis dari user
                    'no_telp' => $request->no_telp,// +62
                    'email' => $request->email,
                    'pendidikan' => $request->pendidikan,//pendidikan terakhir
                    'pekerjaan' => $request->pekerjaan,
                    'range_gaji' => $request->range_gaji,
                    'status' => $request->status,//status pernikahan sudah menika atau belum
                    'jumlah_anak' => $request->jumlah_anak,
                    'role' => 'user',
                    'password' => Hash::make($request->password),
                ]);

            $verification = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $users->id, 'hash' => sha1($users->getEmailForVerification())]
            );

            $SendEmailVerifyJob = new SendEmailVerifyJob($users, $verification);
            dispatch($SendEmailVerifyJob);

            return response()->json([
                'Massage' => 'userCreatedSuccessfully, Please Check Your Email',
                'user' => $users
            ]);
    }

    

    public function registerAdmin(Request $request){
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'no_telp' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validate->fails()) {
                return response()->json([
                    'error'=>true,
                    'message'=>$validate->errors()
                ]);
            }

                $users = User::create([
                    'name' => $request->name,
                    'no_telp' => $request->no_telp,
                    'email' => $request->email,
                    'role' => 'admin',
                    'email_verified_at' => Carbon::now(),
                    'password' => Hash::make($request->password),
                ]);

            $verification = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $users->id, 'hash' => sha1($users->getEmailForVerification())]
            );

            $SendEmailVerifyJob = new SendEmailVerifyJob($users, $verification);
            dispatch($SendEmailVerifyJob);

            return response()->json([
                'Massage' => 'userCreatedSuccessfully, Please Check Your Email',
                'user' => $users
            ]);
    }

    public function deleteAcc($id){
        $user = User::where('id', $id)->delete();

        if($user){
            return response()->json([
                'Massage' => 'userDeleteSuccessfully',
            ]);
        }
    }
}
