@extends('layouts.app')

@section('title', 'home')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{-- {{ Breadcrumbs::render('product', $product) }} --}}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Dashboard <?php echo Auth::user()->roles->first()->name; ?></h1>


    <div class="row">

        @include('layouts.partials.aside')

        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

            <!--div class="card">
                <div class="card-header">Dashboard <?php echo Auth::user()->roles->first()->name; ?></div-->

                {{-- <div class="card-body"> --}}
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    


                    {{-- You are logged in! --}}
                    @role('owner')
                        
    
                        {{-- Actions --}}
                        {{-- @if( $actions->count() )
                            <h4 id="actions">Table last actions</h4>
                            @include('layouts.partials.actions')
                        @endif --}}
                        {{-- /Actions --}}


                        {{-- <h4>Task List</h4>

                        <ol class="numlist">
                            <li class="complete"  >залить сайт на сервер</li>
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
                            <li class="incomplete">дописать историю заказа</li>
                            <li class="incomplete">добавить всплывающие запросы подтверждения</li>
                            <li class="incomplete">изменить цвета статусов заказов</li>
                            <li class="complete"  >добавить 'slug' во все модели (?)</li>
                            <li class="incomplete">добавить список желаний</li>
                            <li class="incomplete">обдумать концепцию админки</li>
                            <li class="incomplete">add canonical link</li>
                            <li class="incomplete">добавить plain в email https://laravel.ru/posts/272</li>
                            <li class="incomplete">удаление изображений удаленного товара. вроде сделано. проверить.</li>
                            <li class="complete"  >редактирование настроек пользователей (бд) и магазина (бд?) (?)</li>
                            <li class="incomplete">отправка писем</li>
                            <li class="incomplete">добавить noimage в rewatermark</li>
                            <li class="incomplete">список задач на сайте</li>
                            <li class="complete"  >создать notification Updated($product)</li>
                            <li class="complete"  >alidation images</li>
                            <li class="incomplete">в избранное</li>
                            <li class="complete">поиск по описанию (?)</li>
                            <li class="complete">отправка уведомлений при редактировании и удалении товаров</li>
                            <li class="incomplete">недопущение изменения статуса заказа без ознакомления с комментарием (с записью кто ознакомился)</li>
                            <li class="incomplete">удаление заказа (?)</li>
                            <li class="incomplete">изменить вид селектов в настройках</li>
                            <li class="incomplete">добавить валидацию изображений</li>
                            <li class="complete"  >поправить хлебные крошки для категорий</li>
                            <li class="complete">добавить остальные хлебные крошки</li>
                            <li class="incomplete">разобраться с полями: display_name, title, slug etc. вывести общий знаменатель в дескрипшн события.</li>
                            <li class="incomplete">разобраться с полем rank при редактировании роли</li>
                            <li class="incomplete">добавить фильтр по материалам</li>
                            <li class="incomplete">убраться в .env</li>
                            <li class="incomplete">разбить информацию в админке по частям. слишком много всего.</li>
                            <li class="incomplete">написать ежедневник</li>
                            <li class="incomplete">ADD DELETE PRODUCT EMAIL!</li>
                            <li class="incomplete">добавить в сетку col-md и прочие</li>
                            <li class="incomplete">add email, session flash in CategoryController!</li>
                            <li class="incomplete">убрать галерею товаров, если изображение одно</li>
                            <li class="incomplete">реализовать наличие у пользователя только одной роли!</li>
                            <li class="incomplete">добавить комментарии в историю</li>
                            <li class="incomplete">поправить переменные в тайтлах (@@section('title', 'Copy product {\{ $product->name }\}')
                            <li class="incomplete">Стилизация радиокнопок и чекбоксов <a href="https://www.internet-technologies.ru/articles/stilizaciya-radioknopok-i-chekboksov-pri-pomoschi-css.html">при помощи CSS</a> </li>
                            <li class="incomplete"><a href="http://shpargalkablog.ru/2013/08/checked.html">Я ознакомлен и принимаю условия договора</a> </li>
                            <li class="incomplete">добавить в настройки пагинацию</li>
                            <li class="incomplete">добавить проверку изменений при обновлении всего</li>
                            <li class="incomplete">добавить в события конкретики (более полное описание изменений)</li>
                            <li class="incomplete">изменить roles.edit в соответствии со <a href="http://yapro.ru/web-master/php/checkbox-radio.html">значением поля</a> </li>
                            <li class="incomplete">сравнение товаров!!!</li>
                        </ol>


                        <br>
                        <br> --}}


                        <h4>Deploy</h4>
                        <ol class="numlist">
                            <li class="incomplete">Создать на сервере пользователя, сайт и базу данных</li>
                            {{-- <li class="incomplete">лллллллл</li> --}}
                            <li class="incomplete"  >Добавить id_rsa.pub на github</li>
                            <li class="incomplete"  >Клонировать репозитарий</li>
                            <pre><code>
~ git clone -b master git@github.com:yakoffka/laravel-store.git public_html
                            </code></pre>
                            <li class="incomplete"  >БЕЗ ПЕРЕЗАПИСИ скопировать поверх клонированных файлов локальные файлы (изображения, .env и прочие)</li>
                            <li class="incomplete"  >Удалить лишнее (логи, )</li>
                            <li class="incomplete"  >Изменить настройки в .env</li>

                            <li class="incomplete"  >Cоздать ссылку на storage ('php artisan storage:link')</li>
                            <li>Очереди:
                                <ol class="numlist">
                                    {{-- <li class="incomplete"  >Произвести запуск обработчика очереди ('php artisan queue:work'). Чтобы процесс queue:work выполнялся в фоне постоянно, используйте монитор процессов, такой как Supervisor, для проверки, что обработчик очереди не остановился.</li> --}}
                                    <li class="incomplete"  >В файле .env: QUEUE_CONNECTION=sync заменить на QUEUE_CONNECTION=database</li>
                                    <li class="incomplete"  >Установить и настроить Supervisor
                                        <pre><code>
# apt install supervisor
                                        </code></pre>
                                            Добавить конфигурационный файл: /etc/supervisor/conf.d/laravel_worker.conf
                                        <pre><code>
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php {путь/к/файлу}/artisan queue:work database --sleep=3 --tries=3
autostart=true
autorestart=true
user={имя_пользователя}
numprocs=8
redirect_stderr=true
stdout_logfile={путь/к/файлу}/storage/logs/worker.log
                                        </code></pre>
                                            Применить настройки
                                        <pre><code>
# nano /etc/supervisor/conf.d/laravel_worker.conf
# sudo supervisorctl reread
laravel-worker: available
# sudo supervisorctl update
laravel-worker: added process group
# sudo supervisorctl start laravel-worker:*
                                        </code></pre>
                                    </li>
                                    {{-- <li class="incomplete"  >pppppp</li> --}}
                                </ol>
                            </li>
                            <li class="incomplete"  >Выполнить
                                <pre><code>
~ composer install
~ php artisan migrate:refresh --seed
                                </code></pre>
                            </li>
                            <li class="incomplete">Добавить в .gitignore '/config/settings.php'</li>
                            <li class="incomplete">voila</li>
                            {{-- <li class="incomplete">лллллллл</li> --}}
                            {{-- <li class="incomplete">лллллллл</li> --}}
                            {{-- <li class="incomplete">лллллллл</li> --}}
                            {{-- <li class="incomplete"  >pppppp</li> --}}
                        </ol>



                        <h4>Create filters</h4>
                        {{-- https://coursehunters.net/course/filtry-v-laravel --}}
                        <ol class="numlist">
                            <li class="incomplete">В модели создаём метод scopeFilter, возвращающий новый экземпляр класса ProductFilters (не забыть прописать use App\Filters\Product\ProductFilters;)</li>
                            <li class="incomplete">Создаём директории app/Filters; app/Filters/Product, а в ней - класс app/Filters/Product/ProductFilters.php</li>
                            <li class="incomplete">Выносим свойство $request и методы __construct() и filters() в абстрактный класс app/Filters/FiltersAbstract.php (и, собственно, создаём его)</li>
                            <li class="incomplete">Добавляем свойство protected $filters = []; в FiltersAbstract</li>
                            {{-- <li class="incomplete"></li>
                            <li class="incomplete"></li>
                            <li class="incomplete"></li> --}}
                        </ol>
                    @endrole


                    {{-- You are logged in! --}}
                    @role(['owner'])


                    <h4>Регистрация в yandex</h4>

                    <code>
                        <pre>
    2019.09.15

    https://passport.yandex.ru/registration?from=mail&origin=hostroot_homer_auth_L_ru&retpath=https%3A%2F%2Fmail.yandex.ru%2F&process_uuid=4c0ed96b-9625-4508-a768-3d8c7392e00c
    Регистрация rope-kit@yandex.ru
        Имя Канат
        Фамилия Комплект
        Придумайте логин    rope-kit
        Придумайте пароль   kjhil7f5
        У меня нет телефона
        Фамилия вашего любимого музыканта rthetherthsdgf
        Ваш аккаунт готов!

    https://developer.tech.yandex.ru/
    Подключить API
        Добавить ключ
        Название ключа yandexmapkey
            yandexmapkey
            4d24c871-78b4-4844-8088-0b919c191b85
                        </pre>
                    </code>



                        <h4>History</h4>

                        <ol>

                            <li>
                                Установка <a href="https://github.com/Intervention/image">Intervention/image (стоит в зависимостях у UniSharp/laravel-filemanager
                                    branch_name = 'intervention_image'
                                <code>
                                    composer diagnose
                                    composer require intervention/image
                                    php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravel5"
                                </code>
                            </li>

                            <li>
                                Для интеграции wysiwyg-редактора выбрал 
                                <a href="https://github.com/UniSharp/laravel-filemanager">UniSharp/laravel-filemanager</a>.
                                Дальше надеюсь заменить ImageYoTrait на intervention/image, который стоит в зависимостях.
                                <a href="https://unisharp.github.io/laravel-filemanager/installation">installation</a>
                                <code>
                                    <pre>
    vagrant@homestead:~/projects/kk$ composer require unisharp/laravel-filemanager:~1.8
    vagrant@homestead:~/projects/kk$ php artisan vendor:publish --tag=lfm_config
    Copied File [/vendor/unisharp/laravel-filemanager/src/config/lfm.php] To [/config/lfm.php]
    Publishing complete.
    vagrant@homestead:~/projects/kk$ php artisan vendor:publish --tag=lfm_public
    Copied Directory [/vendor/unisharp/laravel-filemanager/public] To [/public/vendor/laravel-filemanager]
    Publishing complete.
    vagrant@homestead:~/projects/kk$ 
    php artisan route:clear
    php artisan config:clear
                                    </pre>
                                </code>
                            </li>
                            
                            {{-- <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li> --}}
                        </ol>
                    @endrole


                    {{-- You are logged in! --}}
                    @role(['owner', 'manager'])
                        <h4>Памятка по наполнению магазина</h4>

                        <ol>
                            <li>Категория может содержать либо подкатегории, либо товары.</li>
                            <li>Каждый товар или категория должны иметь уникальное имя.</li>
                            <li>Категория верхнего уровня (корневая директория) может содержать либо подкатегории, либо товары. Подкатегория (категория второго уровня) может содержать только товары.</li>
                            <li>Порядок сортировки (0-9) отвечает за очередность вывода элемента (товара, категории, изображения товара) на странице. Чем меньше порядок, тем ниже будет выведен элемент на витрине магазина.</li>
                            <li>Для товаров порядок сортировки имеет значение только при сортировке по-умолчанию. При сортировке по цене игнорируется.</li>
                            {{-- <li></li> --}}
                            {{-- <li></li> --}}
                        </ol>
                    @endrole

                {{-- </div>
            </div> --}}
        {{-- </div> --}}
    </div>
</div>
@endsection
