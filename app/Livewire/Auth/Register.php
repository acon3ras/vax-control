<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Register extends Component
{
    public $rut = '';
    public $name = '';
    public $email = '';
    public $unit_service = '';
    public $position_title = '';

    public function rules() 
    {
        $allowedDomains = \App\Models\SystemSetting::getValue('allowed_domains', 'saludaysen.cl,ssaaysen.cl,ssaysen.cl');
        // Escape dots for regex
        $domains = explode(',', $allowedDomains);
        $escapedDomains = array_map(function($d) { return preg_quote(trim($d)); }, $domains);
        $domainPattern = implode('|', $escapedDomains);

        return [
            'rut' => ['required', 'string', 'unique:users,rut'], 
            'name' => ['required', 'string', 'min:5'],
            'email' => [
                'required', 
                'email', 
                'unique:users,email', 
                'regex:/^[a-zA-Z0-9._%+-]+@(' . $domainPattern . ')$/i'
            ],
            'unit_service' => ['required', 'string'],
            'position_title' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        $allowedDomains = \App\Models\SystemSetting::getValue('allowed_domains', 'saludaysen.cl,ssaaysen.cl,ssaysen.cl');
        $formattedDomains = implode(', @', explode(',', $allowedDomains));

        return [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debes ingresar un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'email.regex' => 'Solo se permiten correos institucionales (@' . $formattedDomains . ').',
            'rut.required' => 'El RUT es obligatorio.',
            'rut.unique' => 'Este RUT ya se encuentra registrado.',
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 5 caracteres.',
            'unit_service.required' => 'La unidad es obligatoria.',
            'position_title.required' => 'El cargo es obligatorio.',
        ];
    }

    public function updatedRut()
    {
        $this->validateOnly('rut');
        // TODO: Add refined Chilean RUT validation logic
    }

    public function register()
    {
        $this->validate();

        if (!$this->validateRut($this->rut)) {
            $this->addError('rut', 'El RUT ingresado no es válido.');
            return;
        }

        if (!$this->validateNameFormat($this->name)) {
            $this->addError('name', 'Debe ingresar al menos un nombre y dos apellidos.');
            return;
        }

        $user = \App\Models\User::create([
            'rut' => $this->rut,
            'name' => $this->name,
            'email' => $this->email,
            'unit_service' => $this->unit_service,
            'position_title' => $this->position_title,
            'status' => 'PENDING',
            'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)), // Temp password
        ]);
        
        // Generate Signed URL for password setup
        $url = \Illuminate\Support\Facades\URL::signedRoute('activation', ['user' => $user->id]);
        
        // Send Activation Email
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\AccountActivation($user, $url));
            session()->flash('message', 'Registro exitoso. Se ha enviado un enlace a su correo para activar la cuenta.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail Error: ' . $e->getMessage());
            session()->flash('message', 'Registro guardado, pero hubo un error enviando el correo. Contacte a soporte.');
        }

        $this->reset();
    }

    protected function validateRut($rut)
    {
        $rut = preg_replace('/[^k0-9]/i', '', $rut);
        $dv  = substr($rut, -1);
        $numero = substr($rut, 0, strlen($rut)-1);
        $i = 2;
        $suma = 0;
        foreach(array_reverse(str_split($numero)) as $v)
        {
            if($i==8)
                $i = 2;

            $suma += $v * $i;
            ++$i;
        }

        $dvr = 11 - ($suma % 11);
        
        if($dvr == 11)
            $dvr = 0;
        if($dvr == 10)
            $dvr = 'K';

        if(strtoupper((string)$dv) == strtoupper((string)$dvr))
            return true;
        else
            return false;
    }

    protected function validateNameFormat($name)
    {
        $words = array_filter(explode(' ', trim($name)));
        return count($words) >= 3; 
    }
}
