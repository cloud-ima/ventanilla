<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidRut implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $rut = preg_replace('/[^0-9kK]/', '', $value);
        $body = substr($rut, 0, -1);
        $dv = strtoupper(substr($rut, -1));

        $sum = 0;
        $multiplier = 2;

        for ($i = strlen($body) - 1; $i >= 0; $i--) {
            $sum += intval($body[$i]) * $multiplier;
            $multiplier = ($multiplier == 7) ? 2 : $multiplier + 1;
        }

        $remainder = $sum % 11;
        $calculatedDv = 11 - $remainder;

        if ($calculatedDv == 11) {
            $calculatedDv = '0';
        } elseif ($calculatedDv == 10) {
            $calculatedDv = 'K';
        } else {
            $calculatedDv = strval($calculatedDv);
        }

        if ($calculatedDv !== $dv) {
            $fail('validation.custom.rut.valid_rut')->translate();
        }
    }
}
