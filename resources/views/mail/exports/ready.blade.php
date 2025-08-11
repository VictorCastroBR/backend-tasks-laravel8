@component('mail::message')
# Export pronto ✅

Seu arquivo CSV foi gerado.

@isset($deepLink)
@component('mail::button', ['url' => $deepLink])
Abrir para baixar
@endcomponent
@else
Acesse a plataforma e vá até **Exportações** para baixar.
@endisset

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
