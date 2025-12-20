@component('mail::message')
{{-- Logo --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('images/logo-light.png') }}" alt="Institut Hijau Indonesia" style="width: 120px; height: auto;">
</div>

# Kode Verifikasi Email Anda

Gunakan kode OTP berikut untuk memverifikasi alamat email Anda:

@component('mail::panel', ['color' => 'success'])
<span style="font-size: 1.5rem; font-weight: bold;">{{ $otp }}</span>
@endcomponent

Kode ini hanya berlaku selama 10 menit.

Jika Anda tidak merasa mendaftar, mohon abaikan email ini.

{{-- Footer --}}
<div style="margin-top: 20px; font-size: 0.85rem; color: #555;">
    Jangan membalas email ini, karena ini adalah pesan otomatis dari sistem.<br>
    Terima kasih,<br>
    <strong>Institut Hijau Indonesia</strong>
</div>
@endcomponent
