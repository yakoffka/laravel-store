@component('mail::message')
# {{ $subject }}

Пользователь {{ $username }} {{ __($type . '_comment') }} категорию:

"{{ $category->name }}"

Для просмотра категории перейдите по ссылке ниже.

@component('mail::button', ['url' => route('categories.adminshow', ['categories' => $category->id])])
{{ __('show') }}
@endcomponent

__('Thanks'),<br>
{{ config('app.name') }}
@endcomponent
