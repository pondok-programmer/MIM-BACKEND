<?php

namespace App\Http\Controllers\LoginSystem;

use App\Models\User;
use App\Jobs\SendOtpJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthMobileController extends Controller
{
    
    public function registerMobile(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fails',
                'errors' => $validator->errors()
            ]);
        }

        $verificationOtp = Str::random(1000, 9999);
        $users = new User();
        $users->name = $request->name;
        $users->email = $request->email;
        $users->otp_code = $verificationOtp;
        $users->password = Hash::make($request->password);
        $users->role = 'user';
        $users->save();

        $SendEmailVerifyJob = new SendOtpJob($users, $verificationOtp);
        dispatch($SendEmailVerifyJob);
        
        return response()->json([
            'Massage' => 'userCreatedSuccessfully',
        ]);
    }

    public function regiseterMobileAdmin(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fails',
                'errors' => $validator->errors()
            ]);
        }
        
        $verificationOtp = Str::random(1000, 9999);
        $users = new User();
        $users->name = $request->name;
        $users->email = $request->email;
        $users->otp_code = $verificationOtp;
        $users->password = Hash::make($request->password);
        $users->role = 'admin';
        $users->save();

        $SendEmailVerifyJob = new SendOtpJob($users, $verificationOtp);
        dispatch($SendEmailVerifyJob);
        
        return response()->json([
            'Massage' => 'userCreatedSuccessfully',
        ]);
    }

}
