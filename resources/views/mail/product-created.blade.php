@component('mail::message')
# Introduction

Created new product {{ $product->name }}

@component('mail::button', ['url' => route('products.show', ['product' => $product->id])])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
