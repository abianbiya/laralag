<?php

namespace Abianbiya\Laralag\Seeders;

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('role')->delete();
        
        \DB::table('role')->insert(array (
            0 => 
            array (
                'id' => '9c13f5cb-d4e0-45f2-9edb-1b4e633bb256',
                'slug' => 'root',
                'nama' => 'Root',
                'tags' => 'root',
                'created_at' => '2024-05-19 05:10:20',
                'updated_at' => '2024-05-19 05:10:20',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
            1 => 
            array (
                'id' => '9c13f5db-4b68-4268-b1a7-e79ad43d9f9f',
                'slug' => 'admin',
                'nama' => 'Admin',
                'tags' => 'admin',
                'created_at' => '2024-05-19 05:10:31',
                'updated_at' => '2024-05-19 05:10:31',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
        ));
        
        
    }
}