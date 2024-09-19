<?php

namespace App\Livewire\Auth;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class MyResetPassword extends Component
{
    
    public $email;
    public $password;
    public $password_confirmation;
    public $token;
    public $isVerified = false;
    protected $hasher;
    public $passwordType = 'password';



    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
        'token' => 'required',
    ];

    public function mount($token, $email,Hasher $hasher)
    {
        $this->hasher = $hasher;
        $this->token = $token;
        $this->email = $email;
        // Validate the token
        $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $email)
                ->first();
                // dd(Carbon::parse($resetRecord->created_at)->addMinutes(2)->isPast());
                
        if(!$this->hasher->check($token, $resetRecord->token) ){
            $this->isVerified = false;
        }else{
            if(Carbon::parse($resetRecord->created_at)->addMinutes(2)->isPast()){
                $this->isVerified = false;
            }else{
                $this->isVerified = true;
            }
        }
    }

    public function resetPassword()
    {
        $this->validate();

        $response = Password::reset([
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'token' => $this->token,
        ], function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();

            // event(new MyPasswordReset($user));
        });

        if ($response == Password::PASSWORD_RESET) {
            session()->flash('message', 'Password has been reset successfully.');
            return redirect()->route('login');
        } else {
            session()->flash('error', 'Failed to reset password.');
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
        return view('livewire.auth.my-reset-password');
    }
}
