<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Password extends Component
{
    #[Validate('required')]
    public $current_password;
    #[Validate('required|min:8|max:30')]
    public $new_password;

    public function render()
    {
        return view('livewire.user.password');
    }

    public function updatePassword()
    {
        $this->validate();
        if (!Hash::check($this->current_password, auth()->user()->password)) {
            $this->addError('current_password', "Invalid Password");
            return;
        }

        $user = auth()->user();
        $user->password = Hash::make($this->new_password);
        $user->save();

        // Reset the form values
        $this->reset('current_password', 'new_password');
        $this->dispatch('flash-message');
    }
}
