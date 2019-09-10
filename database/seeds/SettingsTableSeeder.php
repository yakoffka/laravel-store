<?php

use Illuminate\Database\Seeder;
use App\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [

            // settings for emails
            [
                'group' => 'emails',
                'name_group' => 'Настройки отправки email-уведомлений',
                'type' => 'checkbox',
                'name' => 'email_new_order',
                'display_name' => 'email-уведомления при создании заказа',
                'description' => 'Разрешает либо запрещает отправку email-уведомлений при создании нового заказа.',
                'permissible_values' => [
                    [0, 'выключено'],
                    [1, 'включено'],
                ],
                'default_value' => 0,
            ],
            [
                'group' => 'emails',
                'name_group' => 'Настройки отправки email-уведомлений',
                'type' => 'checkbox',
                'name' => 'email_update_order',
                'display_name' => 'email-уведомления при изменении заказа',
                'description' => 'Разрешает либо запрещает отправку email-уведомлений при изменении нового заказа.',
                'permissible_values' => [
                    [0, 'выключено'],
                    [1, 'включено'],
                ],
                'default_value' => 0,
            ],
            [
                'group' => 'emails',
                'name_group' => 'Настройки отправки email-уведомлений',
                'type' => 'checkbox',
                'name' => 'email_new_product',
                'display_name' => 'email-уведомления при создании нового товара',
                'description' => 'Разрешает либо запрещает отправку email-уведомлений при создании нового товара.',
                'permissible_values' => [
                    [0, 'выключено'],
                    [1, 'включено'],
                ],
                'default_value' => 0,
            ],
            [
                'group' => 'emails',
                'name_group' => 'Настройки отправки email-уведомлений',
                'type' => 'checkbox',
                'name' => 'email_update_product',
                'display_name' => 'email-уведомления при изменении товара',
                'description' => 'Разрешает либо запрещает отправку email-уведомлений при изменении товара.',
                'permissible_values' => [
                    [0, 'выключено'],
                    [1, 'включено'],
                ],
                'default_value' => 0,
            ],
            [
                'group' => 'emails',
                'name_group' => 'Настройки отправки email-уведомлений',
                'type' => 'select',
                'name' => 'email_send_delay',
                'display_name' => 'задержка отправки email-уведомлений',
                'description' => 'Управление задержкой отправки email-уведомлений при изменении товара.',
                'permissible_values' => [
                    [0,  'без задержки'],
                    [1,  '1 минута'],
                    [2,  '2 минуты'],
                    [3,  '3 минуты'],
                    [5,  '5 минут'],
                    [10, '10 минут'],
                    [30, '30 минут'],
                ],
                'default_value' => 0,
            ],
            [
                'group' => 'emails',
                'name_group' => 'Настройки отправки email-уведомлений',
                'type' => 'email',
                'name' => 'additional_email_bcc',
                'display_name' => 'Дополнительные адреса bcc',
                'description' => 'Укажите дополнительные email для отправки скрытых копий уведомлений (эти адреса не будут показаны другим получателям).',
                'permissible_values' => [],
                'default_value' => 0,
            ],
            

            // settings for products
            [
                'group' => 'products',
                'name_group' => 'Настройки отображения товаров',
                'type' => 'checkbox',
                'name' => 'view_products_whitout_price',
                'display_name' => 'Показывать товары без цены',
                'description' => 'Показывать покупателям товары без цены. При отключении данного пункта вместо цены на товар будет выводиться сообщение из следующего пункта.',
                'permissible_values' => [
                    [0, 'не показывать'],
                    [1, 'показывать'],
                ],
                'default_value' => 1,
            ],
            [
                'group' => 'products',
                'name_group' => 'Настройки отображения товаров',
                'type' => 'text',
                'name' => 'priceless_text',
                'display_name' => 'Текст, выводимый при отсутствии цены на товар',
                'description' => 'Укажите текст, выводимый при отсутствии цены на товар или при отключении отображения цен.',
                'permissible_values' => [],
                'default_value' => 'Цену на данный товар уточняйте у менеджера',
            ],
            [
                'group' => 'products',
                'name_group' => 'Настройки отображения товаров',
                'type' => 'select',
                'name' => 'wysiwyg',
                'display_name' => 'Текстовый редактор',
                'description' => 'Выберите текстовый редактор для редактирования товара.',
                'permissible_values' => [
                    ['none', 'не использовать'],
                    ['srccode', 'исходный код'],
                    // ['summernote', 'текстовый редактор summernote'],
                    // ['ckeditor', 'текстовый редактор ckeditor'],
                    ['tinymce', 'текстовый редактор tinymce'],
                ],
                'default_value' => 0,
            ],
            [
                'group' => 'products',
                'name_group' => 'Настройки отображения товаров',
                'type' => 'checkbox',
                'name' => 'describe_wysiwyg',
                'display_name' => 'Использовать текстовый редактор для редактирования описания товара',
                'description' => 'Использовать текстовый редактор для редактирования описания товара.',
                'permissible_values' => [
                    [0, 'не использовать'],
                    [1, 'использовать'],
                ],
                'default_value' => 1,
            ],
            [
                'group' => 'products',
                'name_group' => 'Настройки отображения товаров',
                'type' => 'checkbox',
                'name' => 'modification_wysiwyg',
                'display_name' => 'Использовать текстовый редактор для редактирования модификаций товара',
                'description' => 'Использовать текстовый редактор для редактирования модификаций товара.',
                'permissible_values' => [
                    [0, 'не использовать'],
                    [1, 'использовать'],
                ],
                'default_value' => 1,
            ],
            [
                'group' => 'products',
                'name_group' => 'Настройки отображения товаров',
                'type' => 'checkbox',
                'name' => 'working_conditions',
                'display_name' => 'Использовать текстовый редактор для редактирования условий работы товара',
                'description' => 'Использовать текстовый редактор для редактирования условий работы товара.',
                'permissible_values' => [
                    [0, 'не использовать'],
                    [1, 'использовать'],
                ],
                'default_value' => 1,
            ],

        
            // display settrings
            [
                'group' => 'display_settrings',
                'name_group' => 'Настройки отображения',
                'type' => 'checkbox',
                'name' => 'display_cart',
                'display_name' => 'Отображение корзины',
                'description' => 'Включить отображение корзины.',
                'permissible_values' => [
                    [0, 'выключено'],
                    [1, 'включено'],
                ],
                'default_value' => 1,
            ],
            [
                'group' => 'display_settrings',
                'name_group' => 'Настройки отображения',
                'type' => 'checkbox',
                'name' => 'display_prices',
                'display_name' => 'Отображение цен на товары',
                'description' => 'Включить отображение цен на товары.',
                'permissible_values' => [
                    [0, 'выключено'],
                    [1, 'включено'],
                ],
                'default_value' => 1,
            ],
            [
                'group' => 'display_settrings',
                'name_group' => 'Настройки отображения',
                'type' => 'checkbox',
                'name' => 'display_registration',
                'display_name' => 'Отображение ссылки на регистрацию',
                'description' => 'Включить отображение.',
                'permissible_values' => [
                    [0, 'выключено'],
                    [1, 'включено'],
                ],
                'default_value' => 0,
            ],
            [
                'group' => 'display_settrings',
                'name_group' => 'Настройки отображения',
                'type' => 'checkbox',
                'name' => 'display_login',
                'display_name' => 'Отображение ссылки на вход в профиль пользователя',
                'description' => 'Включить отображение.',
                'permissible_values' => [
                    [0, 'выключено'],
                    [1, 'включено'],
                ],
                'default_value' => 1,
            ],
            [
                'group' => 'display_settrings',
                'name_group' => 'Настройки отображения',
                'type' => 'checkbox',
                'name' => 'display_subcategories',
                'display_name' => 'Отображать в категории подкатегории',
                'description' => 'При включении данного пункта в категории будут отображаться подкатегории и товары непосредственно данной категории.
                    </p><p>При отключении - только товары, причем как находящиеся в данной категории, так и товары дочерних подкатегорий.',
                'permissible_values' => [
                    [0, 'выключено'],
                    [1, 'включено'],
                ],
                'default_value' => 1,
            ],


            // settings for filters
            [
                'group' => 'filters',
                'name_group' => 'Настройки отображения фильтров',
                'type' => 'checkbox',
                'name' => 'filter_manufacturers',
                'display_name' => 'Показывать фильтр производителей',
                'description' => 'При отключении данного пункта фильтр производителей выводиться не будет.',
                'permissible_values' => [
                    [0, 'не показывать'],
                    [1, 'показывать'],
                ],
                'default_value' => 1,
            ],
            [
                'group' => 'filters',
                'name_group' => 'Настройки отображения фильтров',
                'type' => 'checkbox',
                'name' => 'filter_categories',
                'display_name' => 'Показывать фильтр категорий',
                'description' => 'При отключении данного пункта фильтр категорий выводиться не будет.',
                'permissible_values' => [
                    [0, 'не показывать'],
                    [1, 'показывать'],
                ],
                'default_value' => 1,
            ],
            [
                'group' => 'filters',
                'name_group' => 'Настройки отображения фильтров',
                'type' => 'checkbox',
                'name' => 'filter_subcategories',
                'display_name' => 'Показывать в фильтре категорий подкатегории',
                'description' => 'При отключении данного пункта в фильтре категорий будут выводиться только категории верхнего уровня.',
                'permissible_values' => [
                    [0, 'не показывать'],
                    [1, 'показывать'],
                ],
                'default_value' => 0,
            ],


        ];



        foreach ($settings as $setting){
            DB::table('settings')->insert([
                'name' => $setting['name'],
                'name_group' => $setting['name_group'],
                'display_name' => $setting['display_name'],
                'description' => $setting['description'],
                'slug' => Str::slug($setting['display_name'], '-'),
                'group' => $setting['group'],
                'type' => $setting['type'],
                'permissible_values' => serialize($setting['permissible_values']),
                'value' => $setting['default_value'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }


        // overwriting the configuration file config/settings.php
        $settings = Setting::get();
        if ( $settings->count() ) {
            $path = __DIR__ . '/../../config/settings.php';
            $fp = fopen($path, 'w');
            fwrite($fp, "<?php\n\n// this file is automatically generated in '" . __METHOD__ . "!' \n\nreturn [\n\n");

            foreach ( $settings as $setting_ ) {
                fwrite($fp, "\t'$setting_->name' => '$setting_->value',\n");
            }

            fwrite($fp, "\n];");
            fclose($fp);
        }

    }
}
