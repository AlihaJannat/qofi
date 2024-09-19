<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;


class AuthController extends Controller
{
    

    public function login()
    {
        return view('frontend.auth.login');
    }
    public function register()
    {
        return view('frontend.auth.register');
    }

    public function  logout()
    {
        auth()->logout();

        return to_route('home');
    }

    public function forgetPassword()
    {
        return view('frontend.auth.forget');
    }


    public function resetPassword($token , $email)
    {
       
        return view('frontend.auth.reset-password',compact('token','email'));
    }
}
