<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Turnstile implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $response = Http::withOptions([
                'verify' => env('APP_ENV') === 'production', // SSL hanya cek di production
            ])->asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => env('TURNSTILE_SECRET_KEY'),
                'response' => $value,
            ]);

            $result = $response->json();

            if (!isset($result['success']) || $result['success'] !== true) {
                $fail('Verifikasi CAPTCHA gagal. Silakan coba lagi.');
            }

        } catch (\Exception $e) {
            $fail('Tidak dapat memverifikasi CAPTCHA. Periksa koneksi internet Anda.');
        }
    }
}
