<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    //
    public function register(Request $request){
        $request->validate([
            'password'=>'required|confirmed',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        
        $token = $user->createToken('mytoken')->plainTextToken;
         
        return response()->json([
            'user'=>$user,
            'token'=>$token,
        ]);
    }
    public function login(Request $request){
       // check email
       $user = User::where('email',$request->email)->first();
       // check password
       if (!$user || !Hash::check($request->password, $user->password)){
           return response()->json(['message'=>'wrong login'], 401);
       }
       // Create Token( as a key) is key to access to api
       $token = $user->createToken('mytoken')->plainTextToken;
        
       return response()->json([
           'user'=>$user,
           'token'=>$token,
       ]); 
    }
    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json("User logged out");
    }
}
