@component('mail::message')
# {{ $subject }}

{{ $body }}

{{ $comment_body}}

@if( $url )
Для просмотра перейдите по ссылке ниже.
@component('mail::button', ['url' => $url])
{{ __('show') }} {{ $model_name }}
@endcomponent
@endif

{{ __('Thanks') }},<br>
{{ config('app.name') }}
@endcomponent
