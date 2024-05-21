<?php

namespace Abianbiya\Laralag\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('users')->delete();
        
        DB::table('users')->insert(array (
            0 => 
            array (
                'id' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'username' => 'admin',
                'email' => 'admin@mail.com',
                'name' => 'Anbiya',
                'email_verified_at' => NULL,
                'password' => '$2y$12$vMdsdbmqHpvagjvgPJIw1uasidjKCL5o/FcONAbRfkKamrGbnkIbS',
                'identitas' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-05-08 15:08:04',
                'updated_at' => '2024-05-08 15:08:04',
                'created_by' => NULL,
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
        ));
        
        
    }
}