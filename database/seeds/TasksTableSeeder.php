<?php

use Illuminate\Database\Seeder;
use App\User;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // seeder tasks
        $count_users = User::count();

        $lorem = 'lorem ipsum, quia dolor sit amet consectetur adipiscing velit, sed quia non-numquam do eius modi tempora incididunt, ut labore et dolore magnam aliquam quaerat voluptatem.';

        for ( $i = 1; $i < 25; $i++ ) {

            $master = rand(3, $count_users-1);
            $slave  = rand($master, $count_users-1);
            $title = 'Title test task #' . $i;

            DB::table('tasks')->insert([
                'master_user_id' => $master,
                'slave_user_id' => $slave,
                'title' => $title,
                'slug' => Str::slug($title, '-'),
                'description' => 'Description for task #' . $i . '. ' . $lorem ,
                'tasksstatus_id' => rand(1, 5),
                'taskspriority_id' => rand(1, 4),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // task for owner
        $arr_task_for_owner = [
            [
                'title' => 'Исправить валидацию в контроллерах',
                'description' => 'Добавить required_with, exists, unique:table,column,except,idColumn, sometimes и прочее. </p><p> http://laravel.su/docs/5.0/validation#controller-validation </p><p> http://laravel.su/docs/5.4/Validation#available-validation-rules.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 2,
            ],

            [
                'title' => 'Учесть субординацию в постановке задач',
                'description' => 'Учесть субординацию в постановке задач.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'Продумать модальную форму',
                'description' => 'public_html/resources/views/includes/modalForm.blade.php.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'Заменить Status на OrderStatus',
                'description' => 'Заменить Status на OrderStatus, StatusesTableSeeder на OrderStatusesTableSeeder, CreateStatusesTable на CreateOrderStatusesTable, etc.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'Изменить обновление записи',
                'description' => 'Делать изменение записи только при изменении.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'Свернуть меню админки',
                'description' => 'Свернуть подпункты как подкатегории в меню каталога.',
                'tasksstatus_id' => 6,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'Уникальность наименования товара',
                'description' => 'Разобраться, нужна-ли. Если да, то добавить валидацию в контроллер.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'Добавить возможность вставки таблиц',
                'description' => '
                    Добавить возможность вставки таблиц при создании и редактировании товаров. Вставка исходного html-кода с последующей чисткой на стороне сервера.
                    </p><p>Добавить поля в модель Product.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Доработать TaskList',
                'description' => 'Доработать TaskList:</p>
                <ol>
                    <li>Добавить отображение задач и поручений в виде карточек.
                    </li><li>Добавить историю.
                    </li><li>Добавить копирование.
                    </li><li>Продумать смену статуса исполнителем (запрет закрытия, невозможность смены статуса при закрытой задаче, etc).
                    </li><li>Добавить фильтрацию.
                    </li>
                </ol>
                ',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'ImageYoTrait',
                'description' => 'Разберись, наконец, с этим трейтом.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'Выпилить users.status',
                'description' => 'К чёрту субординацию. никакого создания ролей после запуска магазина.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'Обработать загрузку больших изображений',
                'description' => 'Обработать загрузку больших изображений. На фронте и бэке. Поставить лимит.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 3,
            ],

            [
                'title' => 'Заменить dev на prod',
                'description' => 'После окончания сменить значение APP_DEBUG в ".env".',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 2,
            ],

            [
                'title' => 'Удаление мусора',
                'description' => 'Продумать удаление каталогов с изображениями при "migrate:refresh --seed".',
                'tasksstatus_id' => 6,
                'taskspriority_id' => 3,
            ],

            [
                'title' => 'Задисейбленный submit в Settings',
                'description' => 'В Settings сделать submit по-умолчанию неактивным. Активировать только при смене соответствующего параметра. Доделать для email_send_delay и additional_email_bcc.',
                'tasksstatus_id' => 2,
                'taskspriority_id' => 3,
            ],

            [
                'title' => 'Удалить rank',
                'description' => 'Удалить rank из table roles. Субординацию соблюдать, опираясь на role.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 3,
            ],

            [
                'title' => 'Добавить подтверждение удаления товаров из корзины',
                'description' => 'Добавить подтверждение удаления товаров из корзины. Затронуть файлы
                <ul> 
                    <li>resources/views/cart/confirmation.blade.php
                    </li><li>resources/views/cart/show.blade.php
                    </li>
                </ul>
                ',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 3,
            ],

            [
                'title' => 'Редирект со страницы оформления заказа',
                'description' => 'Продумать редирект со страницы оформления заказа при удалении последнего товара из корзины.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 3,
            ],

            [
                'title' => 'Перенести меню каталога',
                'description' => 'Перенести меню каталога в главное меню.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 3,
            ],

            [
                'title' => 'Отображать в категориях подкатегории',
                'description' => 'Вместо товаров на главной и в категориях отображать подкатегории (при их наличии) мозаикой.<br>
                <a href="https://grmeh.ru/cat/stropy_remni/stropy_tekstilnye">пример</a></br> Ниже - контакты с картой.',
                'tasksstatus_id' => 6,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Переделать структуру главного меню',
                'description' => '
                <ol>
                    <li>главная</li>
                    <li>контакты</li>
                    <li>документация</li>
                    <li>справочник</li>
                    <li>каталог</li>
                </ol>.',
                'tasksstatus_id' => 6,
                'taskspriority_id' => 2,
            ],

            [
                'title' => 'Убрать цены, корзину и login/register в настройки',
                'description' => 'Убрать цены и корзину в настройки. По умолчанию отображение включить. Регистрацию, наверное, тоже.',
                'tasksstatus_id' => 6,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Удаление задач',
                'description' => 'Добавить возможность удаления задачи из "resources/views/tasks/show.blade.php".',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Поля модели товар',
                'description' => 'Привести поля товара и прочих моделей к виду (список пополнить):
                <ol>
                    <li>title: string - название</li>
                    <li>slug: string - псевдоним для url</li>
                    <li>published: bool - опубликована ли данная категория</li>
                    <li>title: string - должно быть уникальным для каждой страницы и в нем не должно множество раз встречаться ключевые слова. Делайте заголовок красочным и лаконичным.</li>
                    <li>description: text - описание</li>
                    <li>keywords: string - ключевые слова</li>
                    <li>amount: unsignedinteger - количество</li>
                </ol>
                <br>
                Заменить все вхождения "visible" на "published".
                ',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 2,
            ],

            [
                'title' => 'Добавить в настройки отображение пустых категорий',
                'description' => 'Добавить в настройки отображение пустых категорий. или не в настройки.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'Uuid primaryKey',
                'description' => 'Добавить в модель App\User <code>protected $primaryKey = \'uuid\';</code>.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'perPage in models',
                'description' => 'protected $perPage = 25; // Да, вы можете переопределить число записей пагинации (по умолчанию 15) https://github.com/laravel/framework/blob/5.6/src/Illuminate/Database/Eloquent/Model.php',
                'tasksstatus_id' => 5,
                'taskspriority_id' => 1,
                'comment_slave' => 'сделал, но выдаёт ошибку то-ли при сидировании, то-ли при сохранении/изменении модели. сделать позже нормально.'
            ],

            [
                'title' => 'Изменить алгоритм подсчета стоимости корзины',
                'description' => 'Изменить алгоритм подсчета стоимости корзины в зависимости от наличия цен на товары в корзине.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'Разделить отображение и редактирование настроек',
                'description' => 'Разделить отображение и редактирование настроек.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Вынести всплывающие сообщения в fixed block',
                'description' => 'Вынести всплывающие сообщения в fixed block.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'Удаление категорий',
                'description' => 'Добавить удаление категорий с перемещением нахоудаление категорий с перемещением находящихся в них подкатегорий и товаров на уровень выше.',
                'tasksstatus_id' => 3,
                'taskspriority_id' => 1,
                'comment_slave' => 'пока запретил удаление непустых категорий. Обдумать: нужны-ли дальнейшие действия.'
            ],

            [
                'title' => 'Добавить возможность сидирования магазинов различных тематик',
                'description' => 'Добавить возможность сидирования магазинов различных тематик с управлением из .env.',
                'tasksstatus_id' => 6,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Перенести сидирование изображений в отдельный класс',
                'description' => 'В данный момент сидирование в таблицу "images" происходит в файле "ProductTableSeeder", что не есть комильфо. Исправить.',
                'tasksstatus_id' => 6,
                'taskspriority_id' => 2,
            ],

            [
                'title' => 'Добавить отображение фильтров в настройки',
                'description' => 'Добавить в настройки возможность выбора отображения фильтров (не отображать, только категории, категории и подкатегории). часть элементов раскрывать по клику',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 2,
            ],

            [
                'title' => 'SEO: продумать дубли страниц',
                'description' => 'Продумать дубли страниц: либо отключить, либо настроить canonical. Пример: /products и /categories',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 2,
            ],

            [
                'title' => 'SEO: title, description, keywords',
                'description' => 'Продумать формирование title, description, keywords для товаров и категорий.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Вёрстка: сверстать главную страницу',
                'description' => 'сверстать главную страницу.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 3,
            ],

            [
                'title' => 'Вёрстка: сверстать админку',
                'description' => 'сверстать главную страницу.:
                    <ol>
                        <li>admin.layout</li>
                        <li></li>
                        <li></li>
                        <li></li>
                    </ol>
                ',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 3,
            ],

            [
                'title' => 'Вёрстка: видоизменить 404 etc',
                'description' => 'видоизменить 404, 403, 500 etc.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'Model Product',
                'description' => 'Добавить поля:
                    <ol>
                        <li>sort_order</li>
                        <li>materials => material ??</li>
                        <li></li>
                        <li></li>
                    </ol>

                .',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 2,
            ],

            [
                'title' => 'Добавить групповые действия с товарами',
                'description' => 'Добавить групповые действия с товарами.',
                'tasksstatus_id' => 3,
                'taskspriority_id' => 2,
                'comment_slave' => 'верстка сделана. доделать реализацию на backend.',
            ],

            [
                'title' => 'Поправить шаблоны писем',
                'description' => 'Поправить шаблоны писем.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'Доработать роуты',
                'description' => '
                    <ol>
                        <li>отформатировать роуты;</li>
                        <li>
                            Добавить в необходимых случаях к роутам мидлвару:
                            <code>->middleware(\'auth\')</code>;
                        </li>
                        <li>Заменить родительский роут для редактирования категорий;</li>
                        <li>;</li>
                        <li>.</li>
                    </ol>
                    ',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'Вёрстка: чекбоксы',
                'description' => 'Стилизовать чекбоксы в:
                    <ol>
                        <li>в пермишинах ролей;</li>
                        <li>;</li>
                        <li>;</li>
                        <li>.</li>
                    </ol>
                    ',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Выбор категории при создании/редактировании товара',
                'description' => ':
                    <ol>
                        <li>запретить ввод корневой директории (содержащей подкатегории);</li>
                        <li>соответственно запретить ввод категории, содержащей товары для выбора её в качестве корневой категории для подкатегорий;</li>
                        <li>выстроить удобочитаемый список с отступами в селектах;</li>
                        <li>родительские категории сделать неактивными (а лучше в селекте: категория->подкатегория).</li>
                    </ol>
                    ',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Настройки п. 3.5 Отображать в категории подкатегории',
                'description' => 'Разобраться и поправить описание (и поведение приложения) п. 3.5 Отображать в категории подкатегории.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            // [
            //     'title' => '',
            //     'description' => ':
            //         <ol>
            //             <li>;</li>
            //             <li>;</li>
            //             <li>;</li>
            //             <li>.</li>
            //         </ol>
            //         ',
            //     'tasksstatus_id' => 1,
            //     'taskspriority_id' => 1,
            // ],

            // [
            //     'title' => '',
            //     'description' => ':
            //         <ol>
            //             <li>;</li>
            //             <li>;</li>
            //             <li>;</li>
            //             <li>.</li>
            //         </ol>
            //         ',
            //     'tasksstatus_id' => 1,
            //     'taskspriority_id' => 1,
            // ],

            // [
            //     'title' => '',
            //     'description' => ':
            //         <ol>
            //             <li>;</li>
            //             <li>;</li>
            //             <li>;</li>
            //             <li>.</li>
            //         </ol>
            //         ',
            //     'tasksstatus_id' => 1,
            //     'taskspriority_id' => 1,
            // ],

            [
                'title' => 'Перестал работать фильтр',
                'description' => 'После смены концепции отображения (скорее всего) перестал работать фильтр. Поправить.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            // [
            //     'title' => 'rrrrrr',
            //     'description' => 'rrrrrrr.',
            //     'tasksstatus_id' => 1,
            //     'taskspriority_id' => 1,
            // ],

            // [
            //     'title' => 'rrrrrr',
            //     'description' => 'rrrrrrr.',
            //     'tasksstatus_id' => 1,
            //     'taskspriority_id' => 1,
            // ],

            // [
            //     'title' => 'rrrrrr',
            //     'description' => 'rrrrrrr.',
            //     'tasksstatus_id' => 1,
            //     'taskspriority_id' => 1,
            // ],

            // [
            //     'title' => 'rrrrrr',
            //     'description' => 'rrrrrrr.',
            //     'tasksstatus_id' => 1,
            //     'taskspriority_id' => 1,
            // ],

            // [
            //     'title' => 'rrrrrr',
            //     'description' => 'rrrrrrr.',
            //     'tasksstatus_id' => 1,
            //     'taskspriority_id' => 1,
            // ],

        ];

// (`master_user_id`, `slave_user_id`, `title`, `slug`, `description`, `tasksstatus_id`, `taskspriority_id`, `created_at`, `updated_at`) values 
// (2,  2,   Изменить обновление записи, title-test-task-4, Делать изменение записи только при изменении., opened, 1, 2019-08-28 16:45:26, 2019-08-28 16:45:26)


        foreach ( $arr_task_for_owner as $i => $task ) {

            DB::table('tasks')->insert([
                'master_user_id' => 2,
                'slave_user_id' => 2,
                'title' => $task['title'] ?? Str::limit($task->description, 20),
                'slug' => Str::slug($task['title'], '-'),
                'description' => $task['description'],
                'comment_slave' => $task['comment_slave'] ?? '',
                'tasksstatus_id' => $task['tasksstatus_id'],
                'taskspriority_id' => $task['taskspriority_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
