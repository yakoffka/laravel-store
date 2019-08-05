@component('mail::message')
# Отредактирован товар "{{ $product->name }}"

{{-- Товар отредактирован пользователем {{ auth()->user()->name }}. --}}
Для просмотра перейдите по ссылке ниже.

@component('mail::button', ['url' => route('products.show', ['product' => $product->id])])
show
@endcomponent

{{ config('app.name') }}
@endcomponent
