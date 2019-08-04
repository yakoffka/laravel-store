@component('mail::message')
# Создан новый товар "{{ $product->name }}"

Товар создан пользователем {{ auth()->user()->name }}.
Для просмотра перейдите по ссылке ниже.

@component('mail::button', ['url' => route('products.show', ['product' => $product->id])])
show
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
