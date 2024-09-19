<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Profile extends Component
{
    public $user;
    public $isEditing = false;

    #[Rule('required|min:3|max:100')]
    public $first_name;
    #[Rule('required|min:3|max:100')]
    public $last_name;
    #[Rule('required|date|before:today')]
    public $dob;
    #[Rule('required|in:male,female,other')]
    public $gender;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->dob = $user->dob;
        $this->gender = $user->gender;
    }

    public function render()
    {
        return view('livewire.user.profile');
    }

    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
    }

    public function updateUser()
    {
        $this->validate();
        $user = $this->user;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->dob = $this->dob;
        $user->gender = $this->gender;

        $this->toggleEdit();
    }
}
