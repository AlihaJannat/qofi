<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email;
    public string $password;
    public $keepMeLogin;
    public string $passwordType='password';

    public function attemptLogin()
    {
        $user = User::where('email', $this->email)->where('role', 'customer')->first();
        if (!$user || $user->status == 0) {
            return session()->flash('error', 'Login Failure! Email or Password is incorrect');
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->keepMeLogin ? true : false)) {
            session()->flash('message', 'Login Success');
            $route = route('home');
            $this->redirect($route);
        } else {
            return session()->flash('error', 'Login Failure! Email or Password is incorrect');
        }
    }

    public function togglePasswordShow()
    {
        if ($this->passwordType == 'password') {
            $this->passwordType = 'text';
        } else {
            $this->passwordType = 'password';
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
