<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function signup(Request $request){
       $this->validate($request,[
         'name'=>'required',
         'email'=>'required | unique:users',
         'password'=>'required | min:8 | confirmed'
       ]);

       $user = new User([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),

       ]);
       
         $user->save();
         return response()->json(['message'=>'Successfully create user !'], 201);

    }

  public function login(Request $request){
  	$this->validate($request,[
         
         'email'=>'required',
         'password'=>'required',
         'remember_me'=>'boolean',
       ]);
      
      $credentials = request(['email', 'password']);
      if(!Auth::attempt($credentials)){
      	return response()->json(['message'=>'Invalid email or password !'],401);
      }
      $user = $request->user();
      $tokenResult = $user->createToken('Personal Access Token');
      $token = $tokenResult->token;
      if ($request->remember_me) 
      	$token->expires_at  = Carbon::now()->addWeeks(1);
      	
     
      if($token->save()){

      return response()->json([
        'access_token'=> $tokenResult->accessToken,
        'token_type'=> 'Bearer',
        'expires_at'=> Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
        'user'=> Auth::user()->name,
        'role'=> Auth::user()->role_id,
      ]);
      }else{
      	return response()->json(['message'=>'Someting went worng!']);
      }
  }
  

  public function logout(Request $request){
  	$request->user()->token()->revoke();

  	return response()->json(['message'=>'Logout success!']);
  }

  public function user(Request $request){

  	   return response()->json($request->user());
  }

}
