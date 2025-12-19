<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
            'confirmed',
            'regex:/^\S*$/', // Sin espacios en blanco
        ];
    }

    /**
     * Get the custom validation messages for password rules.
     *
     * @return array<string, string>
     */
    protected function passwordMessages(): array
    {
        return [
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña no cumple con los requisitos de seguridad.',
            'password.min' => 'La contraseña no cumple con los requisitos de seguridad.',
            'password.letters' => 'La contraseña no cumple con los requisitos de seguridad.',
            'password.mixed' => 'La contraseña no cumple con los requisitos de seguridad.',
            'password.numbers' => 'La contraseña no cumple con los requisitos de seguridad.',
            'password.symbols' => 'La contraseña no cumple con los requisitos de seguridad.',
            'password.uncompromised' => 'Esta contraseña ha sido comprometida en una brecha de seguridad. Por favor, elige otra.',
            'password.regex' => 'La contraseña no cumple con los requisitos de seguridad.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
