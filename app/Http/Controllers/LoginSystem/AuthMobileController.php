<?php

namespace App\Http\Controllers\LoginSystem;

use Carbon\Carbon;
use App\Models\User;
use App\Jobs\SendOtpJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AuthMobileController extends Controller
{
    
    public function registerMobile(Request $request){
        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fails',
                'errors' => $validator->errors()
            ]);
        }
        do {
            $verificationOtp = mt_rand(1000, 9999);
            $checkCode = User::where('otp_code', $verificationOtp)->first();
        } while ($checkCode);
        
        $users = User::create([
            'name' => $request->name, //full name
            'tgl_lahir' => $request->tgl_lahir, // tahun-bulan-tanggal
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
            'otp_code' => $verificationOtp,
            'password' => Hash::make($request->password),
        ]);
        
        $SendEmailVerifyJob = new SendOtpJob($users, $verificationOtp);
        dispatch($SendEmailVerifyJob);
        
        return response()->json([
            'Massage' => 'userCreatedSuccessfully, Please Check Your Email',
            'User' => $users
        ]);
    }
}
