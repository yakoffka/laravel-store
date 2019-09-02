<?php

use Illuminate\Database\Seeder;
// use App\Setting;

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
        ];
 
        foreach ($settings as $setting){
            DB::table('settings')->insert([
                'name' => $setting['name'],
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
    }
}
