<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return response()->json(['status'=>true, 'message'=>'User is added successfully!'],200);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false, 'message'=>'user is not added!','error'=>$ex->getMessage()],500);
        }
    }

    public function login(Request $request){
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
            $token = $user->createToken('auth_token')->plainTextToken;

            $user['token'] = $token;

            return response()->json(['status'=>true,'message'=>'otp match successfully','data'=>$user],200);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false, 'message'=>'Internal server error','error'=>$ex->getMessage()],500);
        }
    }
}
