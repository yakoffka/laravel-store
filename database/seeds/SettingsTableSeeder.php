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
                'name' => 'email_new_order',
                'display_name' => 'email-уведомления при создании заказа',
                'description' => 'Управление отправкой email-уведомлений при создании нового заказа',
                'permissible_values' => [
                    [0, 'выключено'],
                    [1, 'включено'],
                ],
                'value' => 0,
                'defolt_value' => 1,
            ],

            [
                'group' => 'emails',
                'name' => 'email_update_order',
                'display_name' => 'email-уведомления при изменении заказа',
                'description' => 'Управление отправкой email-уведомлений при изменении нового заказа',
                'permissible_values' => [
                    [0, 'выключено'],
                    [1, 'включено'],
                ],
                'value' => 0,
                'defolt_value' => 1,
            ],
            

            // всегда включено! 
            // [
            //     'group' => 'emails',
            //     'name' => 'email_new_user',
            //     'display_name' => 'email-уведомления при регистрации нового пользователя',
            //     'description' => 'Управление отправкой email-уведомлений при регистрации нового пользователя',
            //     'permissible_values' => [
            //         [0, 'выключено'],
            //         [1, 'включено'],
            //     ],
            //     'value' => 0,
            //     'defolt_value' => 1,
            // ],

            
            [
                'group' => 'emails',
                'name' => 'email_new_product',
                'display_name' => 'email-уведомления при создании нового товара',
                'description' => 'Управление отправкой email-уведомлений при создании нового товара',
                'permissible_values' => [
                    [0, 'выключено'],
                    [1, 'включено'],
                ],
                'value' => 0,
                'defolt_value' => 1,
            ],
            
            [
                'group' => 'emails',
                'name' => 'email_update_product',
                'display_name' => 'email-уведомления при изменении товара',
                'description' => 'Управление отправкой email-уведомлений при изменении товара',
                'permissible_values' => [
                    [0, 'выключено'],
                    [1, 'включено'],
                ],
                'value' => 0,
                'defolt_value' => 1,
            ],
            

            // settings for products
            [
                'group' => 'products',
                'name' => 'view_products_whitout_price',
                'display_name' => 'Показывать товары без цены',
                'description' => 'Управление отображением товаров без цены пользователям',
                'permissible_values' => [
                    [0, 'не показывать'],
                    [1, 'показывать'],
                ],
                'value' => 0,
                'defolt_value' => 1,
            ],
        ];
 
        foreach ($settings as $setting){
            DB::table('settings')->insert([
                'name' => $setting['name'],
                'display_name' => $setting['display_name'],
                'description' => $setting['description'],
                'slug' => Str::slug($setting['description'], '-'),
                'group' => $setting['group'],
                'permissible_values' => serialize($setting['permissible_values']),
                'value' => $setting['defolt_value'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
