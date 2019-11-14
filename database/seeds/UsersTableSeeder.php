<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // !!! ПРИ ИЗМЕНЕНИИ ПОПРАВИТЬ:
        //     app/Http/Controllers/Auth/RegisterController.php ($user->attachRole(8); // user role)
        //     app/Comment.php ('user_id' => auth()->user()->id ?? $this->user_id ?? 7)
        //     app/Category.php ('user_id' => auth()->user()->id ?? $this->user_id ?? 7)
        //     database/migrations/2019_08_21_100545_create_customevents_table.php ($table->unsignedBigInteger('user_id')->default(7); // default unregistered user)
        //     app/Category.php and other models ($event_details['user'] = auth()->user() ?? User::find(7); // !!!Unregistered user)
        //     app/Comment.php (setAuthor)
        //     app/User.php auth()->user() ? ...

        $users = [
            ['name' => 'System',                                    'email' => str_replace('@', '+system@', config('custom.mail_owner')),       'passw' => config('custom.system_user_passw'),      ], 
            ['name' => 'Owner'.config('custom.name_owner'),         'email' => str_replace('@', '+owner@', config('custom.mail_owner')),        'passw' => config('custom.owner_user_passw'),       ], 
            ['name' => 'Developer'.config('custom.name_devel'),     'email' => str_replace('@', '+developer@', config('custom.mail_devel')),    'passw' => config('custom.developer_user_passw'),   ], 
            ['name' => 'Admin'.config('custom.name_owner'),         'email' => str_replace('@', '+admin@', config('custom.mail_owner')),        'passw' => config('custom.admin_user_passw'),       ], 
            ['name' => 'Cmanager'.config('custom.name_owner'),      'email' => str_replace('@', '+cmanager@', config('custom.mail_owner')),     'passw' => config('custom.cmanager_user_passw'),    ], 
            ['name' => 'Smanager'.config('custom.name_owner'),      'email' => str_replace('@', '+smanager@', config('custom.mail_owner')),     'passw' => config('custom.smanager_user_passw'),    ], 
            ['name' => 'Unregistered',                              'email' => str_replace('@', '+unregistered@', config('mail.from.address')), 'passw' => config('custom.unregistered_user_passw'),], 
            ['name' => 'User1'.config('custom.name_owner'),         'email' => str_replace('@', '+ouser@', config('custom.mail_owner')),        'passw' => config('custom.owner_user_passw'),       ], 
            ['name' => 'User1'.config('custom.name_devel'),         'email' => str_replace('@', '+duser@', config('custom.mail_devel')),        'passw' => config('custom.developer_user_passw'),   ], 
        ];

        // if (config('app.env') === 'local') {
        //     $users = array_merge($users, [
        //         ['name' => 'User1'.config('custom.name_owner'),         'email' => str_replace('@', '+user1o@', config('custom.mail_owner')),        'passw' => config('custom.owner_user_passw'), ], 
        //         ['name' => 'User1'.config('custom.name_devel'),         'email' => str_replace('@', '+user1d@', config('custom.mail_devel')),        'passw' => config('custom.developer_user_passw'), ], 
        //     ]);
        // }

        echo 'Please remember the data for accessing the panel of the store:' . "\n";
        foreach ( $users as $user ) {
            DB::table('users')->insert([
                'uuid' => Str::uuid(),
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => bcrypt($user['passw']),
                'status' => 1,
                // 'verify_token' => Str::random(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            echo "\t" . 'name: "' . $user['name'] . '"; ' 
                . 'email (login): "' . $user['email'] 
                . '"; password: "' . $user['passw'] . '"' . ".\n";
        }
    }
}
