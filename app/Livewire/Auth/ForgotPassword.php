<?php

namespace App\Livewire\Auth;

use Livewire\Component;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetLink;

class ForgotPassword extends Component
{
    public $email = '';
    public $status = null;
    public $errorMessage = null;

    protected $rules = [
        'email' => 'required|email|exists:users,email',
    ];

    public function sendResetLink()
    {
        $this->validate();

        // Check if user exists and is active (optional, but good practice)
        $user = \App\Models\User::where('email', $this->email)->first();
        
        if (!$user) {
             // Should verify against 'exists' rule essentially, but double check
             $this->addError('email', 'No encontramos un usuario con ese correo.');
             return;
        }

        // Check for throttling (prevent spam)
        if (Password::broker()->recentlyCreatedToken($user)) {
             $this->addError('email', 'Por favor espere unos minutos antes de solicitar otro enlace.');
             return;
        }

        // We will manually send the email using our Mailable for full control
        // First, create a token
        $token = Password::broker()->createToken($user);

        // Send Email
        try {
            Mail::to($user->email)->send(new PasswordResetLink($token, $user->email));
            $this->status = 'Se ha enviado un enlace a tu correo.';
            $this->email = ''; // Clear input
        } catch (\Exception $e) {
            $this->errorMessage = 'Hubo un error al enviar el correo. Intente más tarde.';
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
