<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class PasswordChangeController extends Controller
{
    public function changePassword(Request $request){

        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required' , 'confirmed' , 'min:8']
        ]);

        $user = $request->user();

        if(!Hash::check($request->input('current_password') , $user->password)){
            return response()->json([
                'message' => 'Current password is incorrect'
            ] , 422);
        }

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return response()->json([
            'message' => "Password updated successfully!"
        ]);
    }
}
