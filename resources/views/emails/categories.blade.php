@component('mail::message')
# {{ $subject }}

{{ $body }}

@if( $url )
Для просмотра категории перейдите по ссылке ниже.
@component('mail::button', ['url' => $url])
{{ __('show') }} {{ $model_name }}
@endcomponent
@endif

{{ __('Thanks') }},<br>
{{ config('app.name') }}
@endcomponent
