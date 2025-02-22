<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Validator;

class RoleController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required|unique:roles|string|max:255',
            'status'=>'required|in:Active,Inactive'
        ]);
        if($validator->fails()){
            $errorMessage = $validator->errors()->first();
            return response()->json(['status'=>true,'message'=>'Validation error!','error'=>$errorMessage],422);
        }

        try {
            Role::create([
                'name' => $request->name,
                'status' => $request->status,
            ]);
            return response()->json(['status'=>true, 'message'=>'Role is added successfully!'],200);
        } catch (\Exception $ex) {
            return response()->json(['status'=>false, 'message'=>'Role is not added!','error'=>$ex->getMessage()],500);
        }
    }
}
