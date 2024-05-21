<?php

namespace Abianbiya\Laralag\Seeders;

use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('role_user')->delete();

        \DB::table('role_user')->insert(array(
            0 =>
            array(
                'id' => '0803f8df-3e00-4d03-834f-671a416b2af6',
                'role_id' => '9c13f5cb-d4e0-45f2-9edb-1b4e633bb256',
                'user_id' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'scope_id' => NULL,
                'created_at' => '2024-05-19 13:08:00',
                'updated_at' => '2024-05-19 13:08:00',
                'deleted_at' => NULL,
                'created_by' => NULL,
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
            1 =>
            array(
                'id' => 'eb71b74a-0506-4aea-b68c-d8fcfec1991c',
                'role_id' => '9c13f5db-4b68-4268-b1a7-e79ad43d9f9f',
                'user_id' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'scope_id' => NULL,
                'created_at' => '2024-05-19 14:57:59',
                'updated_at' => '2024-05-19 14:57:59',
                'deleted_at' => NULL,
                'created_by' => NULL,
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
        ));
        
        
    }
}