<?php

namespace App\Http\Controllers\LoginSystem;

use App\Models\User;
use Hashids\Hashids;
use App\Jobs\SendOtpJob;
use Illuminate\Http\Request;
use App\Jobs\SendEmailVerifyJob;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    public function verify(Request $request, $id){
        if (!$request->hasValidSignature()) {
            return [
                'message' => 'Email verification failed'
            ];
        }
        
        $hashids = new Hashids('your-secret-salt', 10);
        $decodedIds = $hashids->decode($id);
        if (!count($decodedIds)) {
            return view('VerifyEmail.InvalidVerify');
        }
        $realId = $decodedIds[0];
        
        $user = User::find($realId);
        
        if (!$user) {
            return view('VerifyEmail.InvalidVerify');
        }
        
        $responseUser = $user->toArray();
        $responseUser['id'] = $id;  // We use the original hashed ID
        
        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        
            return view('VerifyEmail.SuccessVerify');
        } elseif ($user->email_verified_at) {
            return view('VerifyEmail.HasVerify');
        } else {
            return view('VerifyEmail.InvalidVerify');
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
         
        if($user->otp_expired && now()->greaterThan($user->otp_expired)){

            $user->otp_code = null;
            $user->otp_expired = null;
            $user->save();

            return response()->json([
                'massage'=>'OTP Has Expired, Please Resend OTP'
            ]);
        }
        
            $user->email_verified_at = now();
            $user->otp_code = null;
            $user->otp_expired = null;
            $user->save();
        
            // $token = $user->createToken('Token')->accessToken;
            return view('VerifyEmail.SuccessVerify');
        
    }
    

    public function resendVerification(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if($validator->fails()){
            return response()->json([
                'Error' => true,
                'Massage' => $validator->errors()
            ]); 
        }

        $users = User::where('email', $request->email)->first();

        if(!$users){
            return response()->json([
                'massage'=>'Email not found'
            ]);
        }else{
            if($users->email_verified_at){
                return response()->json([
                    'message' => 'Email already verified.'
                ]);
            }else{
                    $hashids = new Hashids('your-secret-salt', 10);
                    $hashedId = $hashids->encode($users->id);
                    $verification = URL::temporarySignedRoute(
                        'verification.verify',
                        now()->addMinutes(60),
                        ['id' => $hashedId, 'hash' => sha1($users->getEmailForVerification())]
                    );
                    $responseUser = $users->toArray();
                    $responseUser['id'] = $hashedId;
                $SendEmailVerifyJob = new SendEmailVerifyJob($users, $verification);
                dispatch($SendEmailVerifyJob);

                return response()->json([
                    'success'=>true,
                    'massage'=>'Please check your mail to activate account.'
                ]);
            }
        }
    }

    public function resendVerificationOtp(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if($validator->fails()){
            return response()->json([
                'Error' => true,
                'Massage' => $validator->errors()
            ]); 
        }

        $users = User::where('email', $request->email)->first();

        if(!$users){
            return response()->json([
                'massage'=>'Email not found'
            ]);
        }else{
            if($users->email_verified_at){
                return response()->json([
                    'message' => 'Email already verified.'
                ]);
            }else{
                do {
                    $verificationOtp = mt_rand(1000, 9999);
                    $checkCode = User::where('otp_code', $verificationOtp)->first();
                    $users->otp_code = $verificationOtp;
                } while ($checkCode);

                $users->otp_expired = now()->addMinutes(5);
                $users->save();
                $SendEmailVerifyJob = new SendOtpJob($users, $verificationOtp);
                dispatch($SendEmailVerifyJob);

                return response()->json([
                    'success'=>true,
                    'massage'=>'Please check your mail to activate account.',
                    'OTP' => $verificationOtp
                ]);
            }
        }
    }
}
