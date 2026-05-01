<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoContactInfoRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check for Moroccan phone numbers (e.g. 06..., 07..., +212...)
        $phoneRegex = '~(\+212|0)([ \-_/]*)(\d[ \-_/]*){9}~';
        if (preg_match($phoneRegex, $value)) {
            $fail('Pour des raisons de sécurité, les numéros de téléphone ne sont pas autorisés dans ce champ.');
            return;
        }

        // Check for email addresses
        $emailRegex = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';
        if (preg_match($emailRegex, $value)) {
            $fail('Pour des raisons de sécurité, les adresses email ne sont pas autorisées dans ce champ.');
        }
    }
}
