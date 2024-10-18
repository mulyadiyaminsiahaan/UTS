<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function getRegister(Request $request)
    {
        if (Auth::user()) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    public function postRegister(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]); 

        $user->save();

        Auth::login($user);

        return redirect()->route('home');
    }

    public function getLogin(Request $request)
    {
        if (Auth::user()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }
    public function postLogin(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        $validator->after(function ($validator) use ($request) {
            $user = User::where('email', $request->input('email'))->first();    
            if (!$user || !Hash::check($request->input('password'), $user->password)) {
                $validator->errors()->add('password', 'password is incorrect');   
            }  else {  
                Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->has ('remember'));
            }
});

        if ($validator->fails()) {
            return redirect()
                ->route('login')
                ->withErrors($validator)
                ->withInput();
        }

        return redirect()->route('home');
    }

    public function getlogout(request $request)
    {
        Auth::logout();

        return redirect()->route('login');
    }   
}
    
