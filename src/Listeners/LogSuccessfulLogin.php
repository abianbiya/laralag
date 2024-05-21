<?php

namespace Abianbiya\Laralag\Listeners;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->user;
        try {
            
            // get user's role
            $roles = Permission::getRole($user->id);
            if ($roles->count() == 0) $this->logout();
            $active_role = $roles->first()->only(['id', 'role']);

            // get user's menu
            $menus = Permission::getMenu($active_role);

            // get user's privilege
            $privileges = Permission::getPrivilege($active_role);
            $privileges = $privileges->mapWithKeys(function ($item, $key) {
                return [$item['module'] => $item->only(['create', 'read', 'show', 'update', 'delete', 'show_menu'])];
            });

            // store to session
            session(['menus' => $menus]);
            session(['roles' => $roles->pluck('role', 'id')->all()]);
            session(['privileges' => $privileges->all()]);
            session(['active_role' => $active_role]);
        } catch (\Throwable $th) {
            $this->logout();
        }
    }
}
