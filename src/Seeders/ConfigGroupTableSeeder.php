<?php
namespace Abianbiya\Laralag\Seeders;
use Illuminate\Database\Seeder;

class ConfigGroupTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('config_group')->delete();
        \DB::table('config_group')->insert(array(
            0 => array(
                'id' => 'a0000001-0000-4000-8000-000000000001',
                'slug' => 'general',
                'nama' => 'Pengaturan Umum',
                'urutan' => 1,
                'icon' => 'bi bi-gear',
                'is_tampil' => 1,
                'created_at' => '2026-03-13 00:00:00',
                'updated_at' => '2026-03-13 00:00:00',
                'deleted_at' => NULL,
                'created_by' => NULL,
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
            1 => array(
                'id' => 'a0000001-0000-4000-8000-000000000002',
                'slug' => 'display',
                'nama' => 'Tampilan',
                'urutan' => 2,
                'icon' => 'bi bi-display',
                'is_tampil' => 1,
                'created_at' => '2026-03-13 00:00:00',
                'updated_at' => '2026-03-13 00:00:00',
                'deleted_at' => NULL,
                'created_by' => NULL,
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
            2 => array(
                'id' => 'a0000001-0000-4000-8000-000000000003',
                'slug' => 'auth',
                'nama' => 'Autentikasi',
                'urutan' => 3,
                'icon' => 'bi bi-shield-lock',
                'is_tampil' => 1,
                'created_at' => '2026-03-13 00:00:00',
                'updated_at' => '2026-03-13 00:00:00',
                'deleted_at' => NULL,
                'created_by' => NULL,
                'updated_by' => NULL,
                'deleted_by' => NULL,
            ),
        ));
    }
}
