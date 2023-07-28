<?php

namespace App\Http\Controllers\LoginSystem;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\SendForgotPasswordJob;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    public function changePassword(Request $request, $id){
        
        $validate = Validator::make($request->all(),[
            'old_password' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ]);

    if($validate->fails()){
        return response()->json([
            'message' => 'validation Fails',
            'Error  ' =>  $validate->errors()
        ]);
    }
    $user = User::findOrfail($id);

    if(Hash::check($request->old_password, $user->password)){
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message'=> 'Password Successfuly Update'
        ]);
    }
    else{
        return response()->json([
            'message'=> 'Old Password does match'
        ]);
    }
    }

    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email'
        ]);

        if($validator->fails()){
            return response()->json([
                'Error' => true,
                'Message' => $validator->errors()
            ]);
        }
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        } else if (!$user->email_verified_at) {
            return response()->json(['message' => 'Email Not Verify'], 404);
        } else {
            $token = Password::getRepository()->create($user);
        
            // Kirim email dengan link reset password ke pengguna
            $resetLink = 'https://carikuliner@gmail.com/reset-password?token=' . $token;
        
            $SendForgotPasswordJob = new SendForgotPasswordJob($user, $resetLink);
            dispatch($SendForgotPasswordJob);
        
            return response()->json(['message' => 'Reset password link sent successfully']);
        }
        
            
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $response = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($response === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully']);
        } else {
            return response()->json(['message' => 'Failed to reset password'], 400);
        }
    }
}
