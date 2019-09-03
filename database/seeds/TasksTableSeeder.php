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
                'taskspriority_id' => 2,
            ],

            [
                'title' => 'Продумать модальную форму',
                'description' => 'public_html/resources/views/includes/modalForm.blade.php.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 3,
            ],

            [
                'title' => 'Заменить Status на OrderStatus',
                'description' => 'Заменить Status на OrderStatus, StatusesTableSeeder на OrderStatusesTableSeeder, CreateStatusesTable на CreateOrderStatusesTable, etc.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 2,
            ],

            [
                'title' => 'Изменить обновление записи',
                'description' => 'Делать изменение записи только при изменении.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Свернуть меню админки',
                'description' => 'Свернуть подпункты как подкатегории в меню каталога.',
                'tasksstatus_id' => 5,
                'taskspriority_id' => 4,
            ],

            [
                'title' => 'Уникальность наименования товара',
                'description' => 'Разобраться, нужна-ли. Если да, то добавить валидацию в контроллер.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Добавить возможность вставки таблиц',
                'description' => 'Добавить возможность вставки таблиц. Вставка исходного html-кода с последующей чисткой на стороне сервера.',
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
                'taskspriority_id' => 2,
            ],

            [
                'title' => 'ImageYoTrait',
                'description' => 'Разберись, наконец, с этим трейтом.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 2,
            ],

            [
                'title' => 'Выпилить users.status',
                'description' => 'К чёрту субординацию. никакого создания ролей после запуска магазина.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Обработать загрузку больших изображений',
                'description' => 'Обработать загрузку больших изображений. На фронте и бэке. Поставить лимит.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Заменить dev на prod',
                'description' => 'После окончания сменить значение APP_DEBUG в ".env".',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Удаление мусора',
                'description' => 'Продумать удаление каталогов с изображениями при "migrate:refresh --seed".',
                'tasksstatus_id' => 5,
                'taskspriority_id' => 3,
            ],

            [
                'title' => 'Submit в Settings',
                'description' => 'В Settings сделать submit по-умолчанию неактивным. Активировать только при смене соответствующего параметра.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Удалить rank',
                'description' => 'Удалить rank из table roles. Субординацию соблюдать, опираясь на role.',
                'tasksstatus_id' => 1,
                'taskspriority_id' => 2,
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
                'taskspriority_id' => 1,
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
                'tasksstatus_id' => 1,
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
                'tasksstatus_id' => 1,
                'taskspriority_id' => 1,
            ],

            [
                'title' => 'Убрать цены и корзину',
                'description' => 'Убрать цены и корзину в настройки. По умолчанию отключить отображение. Регистрацию, наверное, тоже.',
                'tasksstatus_id' => 1,
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
                'tasksstatus_id' => $task['tasksstatus_id'],
                'taskspriority_id' => $task['taskspriority_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
