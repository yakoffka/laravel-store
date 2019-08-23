@extends('layouts.app')

@section('title', 'home')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col col-sm-9">
            {{-- {{ Breadcrumbs::render('product', $product) }} --}}
        </div>
        <div class="col col-sm-3">
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Dashboard <?php echo Auth::user()->roles->first()->name; ?></h1>


    <div class="row">

        @include('layouts.partials.aside')

        <div class="col col-sm-10 pr-0">

            <!--div class="card">
                <div class="card-header">Dashboard <?php echo Auth::user()->roles->first()->name; ?></div-->

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- You are logged in! --}}
                    @role('owner')
                        
    
                        {{-- Actions --}}
                        @if( $actions->count() )
                            <h4 id="actions">Table last actions</h4>
                            @include('layouts.partials.actions')
                        @endif
                        {{-- /Actions --}}


                        <h4>Task List</h4>
                        <ol>
                            <li class="complete"  >корзина</li>
                            <li class="incomplete">уведомления для пользователей (на сайте)</li>
                            <li class="complete"  >фильтры</li>
                            <li class="complete">поиск</li>
                            <li class="incomplete">подкатегории</li>
                            <li class="incomplete">сортировка (по цене по возрастанию/убыванию, в наличии/под заказ, etc)</li>
                            <li class="incomplete">добавить локализацию https://github.com/caouecs/Laravel-lang (?)</li>
                            <li class="incomplete">ценовые группы</li>
                            <li class="complete"  >уведомления типа "товар добавлен"</li>
                            <li class="complete"  >уведомления о регистрации</li>
                            <li class="complete"  >подтверждение почты https://klisl.com/laravel-email-confirmation.html</li>
                            <li class="complete"  >всплывающее уведомление о необходимости подтверждения почты после регистрации</li>
                            <li class="complete"  >сохранить содержимое корзины незарегистрированоого пользователя после входа</li>
                            <li class="complete"  >поместить почту админа в скрытое поле (?)</li>
                            <li class="complete"  >прописать возможность добавления скрытого получателя почтовых уведомлений в настройках</li>
                            <li class="incomplete active">дописать историю заказа</li>
                            <li class="incomplete">добавить всплывающие запросы подтверждения</li>
                            <li class="incomplete">изменить цвета статусов заказов</li>
                            <li class="complete"  >добавить 'slug' во все модели (?)</li>
                            <li class="incomplete">добавить список желаний</li>
                            <li class="incomplete">обдумать концепцию админки</li>
                            <li class="incomplete">add canonical link</li>
                            <li class="incomplete">добавить plain в email https://laravel.ru/posts/272</li>
                            <li class="incomplete">удаление изображений удаленного товара</li>
                            <li class="complete"  >редактирование настроек пользователей (бд) и магазина (бд?) (?)</li>
                            <li class="incomplete">отправка писем</li>
                            <li class="incomplete">добавить noimage в rewatermark</li>
                            <li class="incomplete">список задач на сайте</li>
                            <li class="complete"  >создать notification Updated($product)</li>
                            <li class="complete"  >alidation images</li>
                            <li class="incomplete">в избранное</li>
                            <li class="incomplete">поиск по описанию (?)</li>
                            <li class="incomplete">отправка уведомлений при редактировании и удалении товаров</li>
                            <li class="incomplete">недопущение изменения статуса заказа без ознакомления с комментарием (с записью кто ознакомился)</li>
                            <li class="incomplete">удаление заказа (?)</li>
                            <li class="incomplete">изменить вид селектов в настройках</li>
                            <li class="incomplete">хлебные крошки  https://github.com/davejamesmiller/laravel-breadcrumbs</li>
                            <li class="incomplete">https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs</li>
                            <li class="incomplete">добавить валидацию изображений</li>
                            <li class="incomplete">разобраться с порядком вывода продуктов</li>
                            <li class="complete"  >поправить хлебные крошки для категорий</li>
                            <li class="incomplete">добавить остальные хлебные крошки</li>
                            <li class="incomplete">разобраться с полями: display_name, title, slug etc. вывести общий знаменатель в дескрипшн события.</li>
                            <li class="incomplete">разобраться с полем rank при редактировании роли</li>
                            <li class="incomplete">добавить фильтр по материалам</li>
                            <li class="incomplete">убраться в .env</li>
                            <li class="incomplete">разбить информацию в админке по частям. слишком много всего.</li>
                            <li class="incomplete">написать ежедневник</li>
                            <li class="incomplete">ADD DELETE PRODUCT EMAIL!</li>
                            <li class="incomplete">добавить в сетку col-md и прочие</li>
                            <li class="incomplete">add email, session flash in CategoryController!</li>
                            <li class="incomplete">разбить товары по чанкам</li>
                            {{-- <li class="incomplete">лллллллл</li> --}}
                            {{-- <li class="incomplete">лллллллл</li> --}}
                            
                        </ol>

                    @endrole

                {{-- </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection
