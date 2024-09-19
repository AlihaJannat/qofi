<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Register extends Component
{
    public string $email;
    public string $password;
    public $keepMeLogin;
    public string $passwordType='password';

    public function render()
    {
        return view('livewire.auth.register');
    }

    public function togglePasswordShow()
    {
        if ($this->passwordType == 'password') {
            $this->passwordType = 'text';
        } else {
            $this->passwordType = 'password';
        }
    }
}
