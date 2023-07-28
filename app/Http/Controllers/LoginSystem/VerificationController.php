<?php

namespace App\Http\Controllers\LoginSystem;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    public function verify(Request $request, $id){
        if(!$request->hasValidSignature()){
            return [
                'message' => 'Email verified fails'
            ];
        }
        
        $user = User::find($id);

        if(!$user->email_verified_at){
            $user->email_verified_at = now();
            $user->save();

            $token = $user->createToken('Token')->accessToken;
            return response()->json([
                'message' => 'Success',
                'token' => $token
            ], 200);

            return response()->json([
                'status' => 'success',
                'message' => 'Email verified successfully'
            ]);
        }else {
            return response()->json([
                'message' => 'Invalid Link',
            ], 422);
        }
        
    }

    public function verifyOtp(Request $request){
        $validator = Validator::make($request->all(), [
            'otp_code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fails',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('otp_code', $request->otp_code)->first();
    
        if ($user && $request->otp_code == $user->otp_code) {
            $user->email_verified_at = now();
            $user->otp_code = null;
            $user->save();
    
            $token = $user->createToken('Token')->accessToken;
            return response()->json([
                'message' => 'Success',
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid email or OTP code',
            ], 422);
        }

    }
}
