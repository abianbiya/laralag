<?php

namespace Abianbiya\Laralag\Seeders;

use Illuminate\Database\Seeder;

class MenuGroupTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu_group')->delete();
        
        \DB::table('menu_group')->insert(array (
            0 => 
            array (
                'id' => '9bfeaf44-a697-48f0-8c63-5b575441ea25',
                'nama' => 'Menu Utama',
                'nama_en' => 'Main Menu',
                'urutan' => 1,
                'is_tampil' => 1,
                'created_at' => '2024-05-08 15:20:45',
                'updated_at' => '2024-05-08 15:59:24',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'deleted_by' => NULL,
            ),
            1 => 
            array (
                'id' => '9bfebe3e-4dfd-4f82-ab68-51c43994d7ad',
                'nama' => 'Pengaturan Aplikasi',
                'nama_en' => 'Application Setting',
                'urutan' => 9,
                'is_tampil' => 1,
                'created_at' => '2024-05-08 16:02:37',
                'updated_at' => '2024-05-18 14:45:15',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'deleted_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
            ),
            2 => 
            array (
                'id' => '9bfebe8a-96bb-4dfa-847d-58440d34f82f',
                'nama' => 'Peralatan Sistem',
                'nama_en' => 'System Tools',
                'urutan' => 10,
                'is_tampil' => 1,
                'created_at' => '2024-05-08 16:03:27',
                'updated_at' => '2024-05-08 16:03:27',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
            3 => 
            array (
                'id' => '9bfece11-bafc-4130-9922-f43590a8fd93',
                'nama' => 'Voluptatum nihil ill',
                'nama_en' => 'Ullam ea necessitati',
                'urutan' => 1,
                'is_tampil' => 0,
                'created_at' => '2024-05-08 16:46:52',
                'updated_at' => '2024-05-08 17:38:29',
                'deleted_at' => '2024-05-08 17:38:29',
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'deleted_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
            ),
            4 => 
            array (
                'id' => '9bfed02c-90b6-48e8-a74c-675e6c45b401',
                'nama' => 'Cillum assumenda qua',
                'nama_en' => 'Aut quo velit sit',
                'urutan' => 23,
                'is_tampil' => 0,
                'created_at' => '2024-05-08 16:52:45',
                'updated_at' => '2024-05-08 17:38:31',
                'deleted_at' => '2024-05-08 17:38:31',
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'deleted_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
            ),
            5 => 
            array (
                'id' => '9bfed0ae-28f6-419a-a586-d214a06fa82b',
                'nama' => 'Voluptate ipsum qui',
                'nama_en' => 'Aut elit facilis su',
                'urutan' => 2,
                'is_tampil' => 0,
                'created_at' => '2024-05-08 16:54:10',
                'updated_at' => '2024-05-09 12:36:30',
                'deleted_at' => '2024-05-09 12:36:30',
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'deleted_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
            ),
        ));
        
        
    }
}