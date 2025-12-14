@component('mail::message')
{{ __('Has sido invitado a unirte al equipo :team.', ['team' => $invitation->team->name]) }}

@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
{{ __('Si no tienes una cuenta, créala con el botón de abajo. Después, haz clic en el botón de aceptación para unirte al equipo:') }}

@component('mail::button', ['url' => route('register')])
{{ __('Crear cuenta') }}
@endcomponent

{{ __('Si ya tienes cuenta, acepta la invitación con el botón de abajo:') }}

@else
{{ __('Acepta esta invitación con el botón de abajo:') }}
@endif


@component('mail::button', ['url' => $acceptUrl])
{{ __('Aceptar invitación') }}
@endcomponent

{{ __('Si no esperabas esta invitación, puedes ignorar este correo.') }}
@endcomponent
