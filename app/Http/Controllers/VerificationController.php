<?php

namespace App\Http\Controllers;

use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    public function sendVerificationCode(Request $request)
    {
        $user = User::find(Auth()->user()->id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $verificationCode = mt_rand(100000, 999999);
        $user->verification_code = $verificationCode;
        $user->save();

        Mail::to($user)->send(new VerificationMail($verificationCode));

        return response()->json(['message' => 'Verification code sent successfully']);
    }
    public function checkVerificationCode(Request $request)
    {
        // Get the user for whom you want to check the verification code
        $user = User::find(Auth()->user()->id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $enteredCode = $request->input('verification_code');

        $savedCode = $user->verification_code;

        if ($enteredCode == $savedCode) {
            $user->is_verified = true;
            $user->save();
            return response()->json(['message' => 'Verification successful']);
        } else {

            return response()->json(['error' => 'Invalid verification code'], 400);
        }
    }
}
