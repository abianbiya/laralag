<?php

namespace Abianbiya\Laralag\Seeders;

use Illuminate\Database\Seeder;

class MenuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu')->delete();
        
        \DB::table('menu')->insert(array (
            
            2 => 
            array (
                'id' => '9c0077c2-a96f-4729-91fc-6021d668a7de',
                'menu_group_id' => '9bfebe3e-4dfd-4f82-ab68-51c43994d7ad',
                'nama' => 'Manajemen Menu',
                'nama_en' => 'Menu Management',
                'icon' => 'bi bi-list',
                'urutan' => 1,
                'is_tampil' => 1,
                'created_at' => '2024-05-09 12:37:11',
                'updated_at' => '2024-05-09 12:37:11',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
            3 => 
            array (
                'id' => '9c0077e0-129e-4308-ba5f-43b089ebcf17',
                'menu_group_id' => '9bfebe3e-4dfd-4f82-ab68-51c43994d7ad',
                'nama' => 'Manajemen Pengguna',
                'nama_en' => 'User Management',
                'icon' => 'bi bi-person-lines-fill',
                'urutan' => 2,
                'is_tampil' => 1,
                'created_at' => '2024-05-09 12:37:31',
                'updated_at' => '2024-05-09 12:39:07',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'deleted_by' => NULL,
            ),
            4 => 
            array (
                'id' => '9c007835-8527-4a74-8158-18fb5a3a501f',
                'menu_group_id' => '9bfebe3e-4dfd-4f82-ab68-51c43994d7ad',
                'nama' => 'Manajemen Akses',
                'nama_en' => 'Role Management',
                'icon' => 'bi bi-person-gear',
                'urutan' => 3,
                'is_tampil' => 1,
                'created_at' => '2024-05-09 12:38:27',
                'updated_at' => '2024-05-09 12:38:27',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
            6 => 
            array (
                'id' => '9c148b32-8a07-492b-babd-886ba4f6a3ac',
                'menu_group_id' => '9bfebe3e-4dfd-4f82-ab68-51c43994d7ad',
                'nama' => 'Manajamen Level Akses',
                'nama_en' => 'Access Role',
                'icon' => 'bi bi-person-lock',
                'urutan' => 4,
                'is_tampil' => 1,
                'created_at' => '2024-05-19 12:08:06',
                'updated_at' => '2024-05-19 12:08:06',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
            7 => 
            array (
                'id' => '9c14979f-d3f9-4e2e-ab02-4a9ce3cf29c8',
                'menu_group_id' => '9bfebe3e-4dfd-4f82-ab68-51c43994d7ad',
                'nama' => 'Manajemen Unit',
                'nama_en' => 'Scope Management',
                'icon' => 'bi bi-grid-1x2',
                'urutan' => 5,
                'is_tampil' => 1,
                'created_at' => '2024-05-19 12:42:51',
                'updated_at' => '2024-05-19 12:42:51',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
        ));
        
        
    }
}