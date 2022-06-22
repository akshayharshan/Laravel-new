<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;




class AuthController extends Controller
{
  public function register(Request $request){

        $Fields = $request->validate([

            'name'=> 'required|string',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string|confirmed'
        ]);

        $user = User::create([

            'name'=>$Fields['name'],
            'email'=>$Fields['email'],
            'password'=>bcrypt($Fields['password'])

        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [

            'user'=>$user,
            'token'=>$token
        ];

        return response($response,201);
  }
  
  public function login(Request $request){

    $Fields = $request->validate([

        
        'email'=>'required|string',
        'password'=>'required|string'
    ]);

        //check email
        $user = User::where('email',$Fields['email'])->first();


        //check password

        if(!$user || !Hash::check( $Fields['password'], $user->password)){

            return response([

                'message'=> 'Bad credentials'
            ], 401);
        }
  
  
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [

            'user'=>$user,
            'token'=>$token
        ];

        return response($response,201);
  
    } 


    public function logout(){

        // Auth::user()->tokens->each(function($token, $key) {
        //     $token->delete();
        // });
        
        auth()->user()->tokens()->delete();
        return response()->json('Successfully logged out');
    
    
    }


}
