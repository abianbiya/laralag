<?php

namespace Abianbiya\Laralag\Seeders;

use Illuminate\Database\Seeder;

class ModuleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('module')->delete();
        
        \DB::table('module')->insert(array (
            0 => 
            array (
                'id' => '9c0097b4-c5b1-444d-b543-0df8ea7cf715',
                'menu_id' => '9c0077c2-a96f-4729-91fc-6021d668a7de',
                'nama' => 'Manajemen Menu',
                'routing' => 'menu.index',
                'permission' => 'menu.index',
                'urutan' => 1,
                'is_tampil' => 1,
                'created_at' => '2024-05-09 14:06:31',
                'updated_at' => '2024-05-09 14:06:31',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
            1 => 
            array (
                'id' => '9c00a29d-6186-4427-9c93-49faea4f48e5',
                'menu_id' => '9c0077c2-a96f-4729-91fc-6021d668a7de',
                'nama' => 'Manajemen Grup Menu',
                'routing' => 'menugroup.index',
                'permission' => 'menugroup.index',
                'urutan' => 2,
                'is_tampil' => 0,
                'created_at' => '2024-05-09 14:37:01',
                'updated_at' => '2024-05-09 15:46:02',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'deleted_by' => NULL,
            ),
            2 => 
            array (
                'id' => '9c00a2b8-e8fd-4d8d-9489-b46094964043',
                'menu_id' => '9c0077c2-a96f-4729-91fc-6021d668a7de',
                'nama' => 'Manajemen Modul',
                'routing' => 'module.index',
                'permission' => 'module.index',
                'urutan' => 3,
                'is_tampil' => 0,
                'created_at' => '2024-05-09 14:37:19',
                'updated_at' => '2024-05-09 15:21:06',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'deleted_by' => NULL,
            ),
            3 => 
            array (
                'id' => '9c00af12-ed0c-4ac1-9140-f433360341a9',
                'menu_id' => '9c0077e0-129e-4308-ba5f-43b089ebcf17',
                'nama' => 'Manajemen Pengguna',
                'routing' => 'user.index',
                'permission' => 'user.index',
                'urutan' => 1,
                'is_tampil' => 1,
                'created_at' => '2024-05-09 15:11:51',
                'updated_at' => '2024-05-09 15:11:51',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
            4 => 
            array (
                'id' => '9c00af6e-217a-4a34-a14a-ae1b20f3147e',
                'menu_id' => '9c007835-8527-4a74-8158-18fb5a3a501f',
                'nama' => 'Permission',
                'routing' => 'permission.index',
                'permission' => 'permission.index',
                'urutan' => 1,
                'is_tampil' => 1,
                'created_at' => '2024-05-09 15:12:51',
                'updated_at' => '2024-05-09 15:12:51',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
            6 => 
            array (
                'id' => '9c00b9ef-23d2-426e-8de0-35ad491c46b9',
                'menu_id' => '9c00b9cf-2f30-4053-83f4-f40597cbc12e',
                'nama' => 'Post',
                'routing' => 'post.index',
                'permission' => 'post.index',
                'urutan' => 1,
                'is_tampil' => 1,
                'created_at' => '2024-05-09 15:42:13',
                'updated_at' => '2024-05-09 15:42:13',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
            7 => 
            array (
                'id' => '9c148b47-92d1-4078-b36c-dd89f95770ce',
                'menu_id' => '9c148b32-8a07-492b-babd-886ba4f6a3ac',
                'nama' => 'Manajamen Level Akses',
                'routing' => 'role.index',
                'permission' => 'role.index',
                'urutan' => 1,
                'is_tampil' => 1,
                'created_at' => '2024-05-19 12:08:20',
                'updated_at' => '2024-05-19 12:08:20',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
            8 => 
            array (
                'id' => '9c1497b1-e58a-42f9-8b40-3649d56cdbb6',
                'menu_id' => '9c14979f-d3f9-4e2e-ab02-4a9ce3cf29c8',
                'nama' => 'Manajemen Unit',
                'routing' => 'scope.index',
                'permission' => 'scope.index',
                'urutan' => 1,
                'is_tampil' => 1,
                'created_at' => '2024-05-19 12:43:02',
                'updated_at' => '2024-05-19 12:43:02',
                'deleted_at' => NULL,
                'created_by' => '9bfeaabc-c71a-442b-8bbb-8b76194ab72d',
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
        ));
        
        
    }
}