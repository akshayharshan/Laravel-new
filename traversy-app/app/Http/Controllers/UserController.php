<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function create(){


        return view('users.register');
    }

    public function store(Request $request){

        $formFields = $request->validate([

            'name'=> ['required','min:3'],
            'email'=> ['required','email',Rule::unique('users','email')],
            'password'=> 'required | confirmed | min:6'

        ]);

        $formFields['password'] = bcrypt($formFields['password'] );

        $user =  User::create($formFields);

        auth()->login($user);

        return redirect('/')->with('message','User Created And Login successfully!');
    }

    public function logout(Request $request){
         auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerate();
        return redirect('/')->with('message','User Logout successfully!');
        
    }

    public function login(){
        
        return view('users.Login');
       
   }

   public function authenticate(Request $request){
        
    $formFields = $request->validate([

        
        'email'=> ['required','email'],
        'password'=> 'required'

    ]);

    if(auth()->attempt($formFields)){
        $request -> session()->regenerate();
        return redirect('/')->with('message','Login successfully!');
    }

    return back()->withErrors(['password'=>'invalid usename or password'])->onlyInput('email');
   
}



}
