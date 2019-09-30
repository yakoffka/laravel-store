<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            /*
            *   ПОРЯДОК СЛЕДОВАНИЯ НЕ НАРУШАТЬ!
            *  используется в database/seeds/PermissionRoleTableSeeder.php
            */
            [
                'name' => 'system',
                'display_name' => 'System',
                'description'  => 'Изначальная сущность ресурса.',
            ],[
                'name' => 'owner',
                'display_name' => 'Owner',
                'description'  => 'Владелец ресурса. Имеет все возможные права.',
            ],[
                'name' => 'developer',
                'display_name' => 'Developer',
                'description'  => 'Разработчик ресурса. Осуществляет разработку и техническую поддержку сайта. Имеет все возможные права.',
            ],[
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description'  => 'Администратор ресурса. Осуществляет контроль корректной работы и регулярным обновлением интернет-ресурса и его содержимым (включая комментарии пользователей).',
            ],[
                'name' => 'cmanager',
                'display_name' => 'Content Manager',
                'description'  => 'Менеджер по наполнению ресурса. Осуществляет за наполнение сайта статьями и товарами.',
            ],[
                'name' => 'smanager',
                'display_name' => 'Sale Manager',
                'description'  => 'Менеджер по продажам. Осуществляет выполнение операций с заказами.',
            ],[
                'name' => 'unregistered',
                'display_name' => 'Unregistered',
                'description'  => 'Неизвестный пользователь ресурса. Вспомнить, зачем он понадобился и записать сюда.',
            ],[
                'name' => 'user',
                'display_name' => 'User',
                'description'  => 'Пользователь ресурса. Имеет возможность покупки и оставления отзывов о товарах и услугах ресурса.',
            ] 
        ];
 
        foreach ($roles as $role){
            DB::table('roles')->insert([
                'name' => $role['name'],
                'display_name' => $role['display_name'],
                'description' => $role['description'],
                'added_by_user_id' => 1, // creator preset roles is System
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
