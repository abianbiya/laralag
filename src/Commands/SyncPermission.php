<?php

namespace Abianbiya\Laralag\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Abianbiya\Laralag\Modules\Permission\Models\Permission;

class SyncPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lag:sync-permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all permission';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $routes = Route::getRoutes();
        $permissions = [];

        foreach ($routes as $route) {
            $actions = $route->getAction();

            if (isset($actions['middleware'])) {
                $middleware = is_array($actions['middleware']) ? $actions['middleware'] : explode('|', $actions['middleware']);

                foreach ($middleware as $m) {
                    if (strpos($m, 'permission:') === 0) {
                        $permission = str_replace('permission:', '', $m);
                        $permissions[] = $permission;
                    }
                }
            }
        }

        $permissions = array_unique($permissions);
        sort($permissions);

        foreach ($permissions as $permission) {
            $pmsn = explode('.', $permission);
            $permission = Permission::firstOrCreate(['slug' => $permission]);
            $permission->group = reset($pmsn);
            $permission->nama = $this->identifyName($permission->slug);
            $permission->action = end($pmsn);
            $permission->save();
            $this->info("Synced permission: $permission->slug ");
        }

        return 0;
    }

    public function identifyName($permission)
    {
        $pmsn = explode('.', $permission);
        $action = end($pmsn);
        $group = str(reset($pmsn))->title();

        if(count($pmsn) > 2){
            $group = str($permission)->replace($action, '')->replace('.', ' ')->title();
        }
        
        switch ($action) {
            case 'index':
                return 'Melihat daftar ' . $group;
                break;
            case 'create':
                return 'Menampilkan form tambah ' . $group;
                break;
            case 'show':
                return 'Menampilkan detail ' . $group;
                break;
            case 'store':
                return 'Menyimpan form tambah ' . $group;
                break;
            case 'edit':
                return 'Menampilkan form edit ' . $group;
                break;
            case 'update':
                return 'Menyimpan form edit ' . $group;
                break;
            case 'destroy':
                return 'Menghapus ' . $group;
                break;
            case 'menu':
                return 'Menampilkan menu ' . $group;
                break;
            default:
                return $action . ' ' . $group;
                break;
        }
    }
    
}
