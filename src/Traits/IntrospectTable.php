<?php

namespace Abianbiya\Laralag\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait IntrospectTable
{
    protected function getTableInfo(string $namaModel, ?string $fallbackTable = null): array
    {
        $tabel = Str::snake($namaModel);
        $database = env('DB_DATABASE') ?: config('database.connections.' . config('database.default') . '.database');
        $ret['tabel'] = $tabel;

        $q = DB::select("select * from information_schema.COLUMNS where TABLE_SCHEMA=? and TABLE_NAME=?", [$database, $tabel]);

        if (empty($q)) {
            $tableName = $fallbackTable ?? ($this->option('table') ?? null);
            if ($tableName) {
                $tabel = $tableName;
                $q = DB::select("select * from information_schema.COLUMNS where TABLE_SCHEMA=? and TABLE_NAME=?", [$database, $tabel]);
            } else {
                $this->error("Table $tabel not found.");
                return [];
            }
        }

        $primary_key = [];
        $counter_primary = 0;

        foreach ($q as $row) {
            $ret['kolom'][$row->COLUMN_NAME] = [
                'is_nullable' => ($row->IS_NULLABLE == "NO" ? false : true),
                'tipe_data' => $row->DATA_TYPE,
                'length' => $row->CHARACTER_MAXIMUM_LENGTH,
                'catatan' => $row->COLUMN_COMMENT,
                'is_primary' => $row->COLUMN_KEY == "PRI",
                'referensi' => null,
                'referensi_kolom' => null,
                'referensi_desc_kolom' => null
            ];

            // Check if the column is potentially a foreign key based on its suffix
            if (Str::endsWith($row->COLUMN_NAME, ['_id', '_slug'])) {
                $relatedModel = Str::camel(Str::beforeLast($row->COLUMN_NAME, '_')); // Assumes the related table model is the prefix of '_id' or '_slug'
                $relatedTable = Str::snake($relatedModel);
                // Check if related table exists
                $checkTableExist = DB::select("SELECT * FROM information_schema.tables WHERE table_schema = ? AND table_name = ? LIMIT 1", [$database, $relatedTable]);
                if(empty($checkTableExist)) {
                    $relatedModel = Str::camel(Str::beforeLast($row->COLUMN_NAME, '_id')); // Assumes the related table model is the prefix of '_id'
                    $relatedTable = Str::snake($relatedModel.'_'.$row->COLUMN_NAME);
                    $checkTableExist = DB::select("SELECT * FROM information_schema.tables WHERE table_schema = ? AND table_name = ? LIMIT 1", [$database, $relatedTable]);
                }

                if (!empty($checkTableExist)) {
                    $ret['kolom'][$row->COLUMN_NAME]['referensi'] = $relatedTable;
                    // Assuming that the primary key of the related table is 'id' and description column could be 'name' or similar
                    $ret['kolom'][$row->COLUMN_NAME]['referensi_kolom'] = 'id';
                    $relatedDescColumn = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND DATA_TYPE IN ('varchar', 'char') ORDER BY CHARACTER_MAXIMUM_LENGTH DESC LIMIT 1", [$database, $relatedTable]);
                    $ret['kolom'][$row->COLUMN_NAME]['referensi_desc_kolom'] = $relatedDescColumn[0]->COLUMN_NAME ?? 'name';
                }
            }
        }

        return $ret;
    }
}
