@component('mail::message')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('image/logo_baru.png') }}" style="max-width: 200px; margin: 0 auto; display: block;" alt="Logo">
</div>

@if (!empty($greeting))
    # {{ $greeting }}
@else
    @if ($level === 'error')
        # Whoops!
    @else
        # Hello!
    @endif
@endif

@foreach ($introLines as $line)
    {{ $line }}

@endforeach

@isset($otp)
<div style="text-align: center; font-size: 24px; font-weight: bold; margin: 20px 0; color: #333;">{{ $otp }}</div>
@endisset

@if (!empty($actionText))
    @component('mail::button', ['url' => $actionUrl, 'color' => $color ?? 'primary'])
        {{ $actionText }}
    @endcomponent
@endif

@foreach ($outroLines as $line)
    {{ $line }}

@endforeach

@if (!empty($salutation))
    {{ $salutation }}
@else
    Regards,<br>{{ config('app.name') }}
@endif
@endcomponent
