<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Rule;
use Livewire\Component;

class ForgetPassword extends Component
{
    #[Rule('required|min:3|max:100')]
    public string $email;

    public function requestForget()
    {
        $this->validate();
        dd($this->email);
    }

    public function render()
    {
        return view('livewire.auth.forget-password');
    }
}
