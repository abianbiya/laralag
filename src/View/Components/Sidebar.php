<?php

namespace Abianbiya\Laralag\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Abianbiya\Laralag\Modules\Menu\Models\Menu;
use Abianbiya\Laralag\Modules\Role\Models\Role;
use Abianbiya\Laralag\Modules\MenuGroup\Models\MenuGroup;

class Sidebar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $user = Auth::user();
        if($user){
            $role = $user->getActiveRole();
            $role = Role::whereSlug($role)->first();
            $permissions = $role->permission ?? [];
            
            $data['menus'] = MenuGroup::with('menu.module')
                                ->whereHas('menu.module', function($query) use ($permissions){
                                    $query->whereIn('permission', $permissions->pluck('slug')->toArray());
                                })
                                ->where('is_tampil',1)
                                ->orderBy('urutan')->get();
        }else{
            $data['menus'] = [];
        }
        return view('Laralag::components.sidebar', $data);
    }
}
