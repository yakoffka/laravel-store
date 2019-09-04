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
                'description' => 'Включить отправку email-уведомлений при создании нового заказа.',
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
                'description' => 'Включить отправку email-уведомлений при изменении нового заказа.',
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
                'description' => 'Включить отправку email-уведомлений при создании нового товара.',
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
                'description' => 'Включить отправку email-уведомлений при изменении товара.',
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
                'description' => 'Показывать покупателям товары без цены.',
                'permissible_values' => [
                    [0, 'не показывать'],
                    [1, 'показывать'],
                ],
                'default_value' => 1,
            ],

        
            // settings for carts_and_price
            [
                'group' => 'carts_and_price',
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
                'group' => 'carts_and_price',
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
                'group' => 'carts_and_price',
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
                'group' => 'carts_and_price',
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
                'group' => 'carts_and_price',
                'name_group' => 'Настройки отображения',
                'type' => 'text',
                'name' => 'priceless_text',
                'display_name' => 'Текст, выводимый при отсутствии цены на товар',
                'description' => 'Укажите текст, выводимый при отсутствии цены на товар или при отключении отображения цен.',
                'permissible_values' => [],
                'default_value' => 'Цену на данный товар уточняйте у менеджера',
            ],

        ];

        foreach ($settings as $setting){
            DB::table('settings')->insert([
                'name' => $setting['name'],
                'name_group' => $setting['name_group'],
                'display_name' => $setting['display_name'],
                'description' => $setting['description'],
                'slug' => Str::slug($setting['description'], '-'),
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
