@component('mail::message')
# {{ $subject }}

Пользователь {{ $username }} {{ __($type . '_comment') }} комментарий:

"{{ $comment->body }}"

Для просмотра данного комментария перейдите по ссылке ниже.

@component('mail::button', ['url' => route('products.show', ['product' => $comment->product_id])])
{{ __('show') }}
@endcomponent

__('Thanks'),<br>
{{ config('app.name') }}
@endcomponent
