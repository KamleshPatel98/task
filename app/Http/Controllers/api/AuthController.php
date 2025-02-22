<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use App\Models\User;
use Auth;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'email'=>'required|unique:users|email|max:255',
            'password'=>'required|min:8|max:12|string'
        ]);
        if($validator->fails()){
            $errorMessage = $validator->errors()->first();
            return response()->json(['status'=>true,'message'=>'Validation error!','error'=>$errorMessage],422);
        }

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
        $validator = Validator::make($request->all(),[
            'email'=>'required|exists:users|email|max:255',
            'password'=>'required|min:8|max:12|string'
        ]);
        if($validator->fails()){
            $errorMessage = $validator->errors()->first();
            return response()->json(['status'=>true,'message'=>'Validation error!','error'=>$errorMessage],422);
        }
    
        try {
            if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            $user['token'] = $token;

            return response()->json(['status'=>true,'message'=>'otp match successfully','data'=>$user],200);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false, 'message'=>'Internal server error','error'=>$ex->getMessage()],500);
        }
    }
}
