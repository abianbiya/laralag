<?php

namespace Abianbiya\Laralag\Traits;

use Illuminate\Support\Facades\Session;
use Abianbiya\Laralag\Models\Permission;
use Abianbiya\Laralag\Modules\Role\Models\Role;
use Abianbiya\Laralag\Modules\Scope\Models\Scope;
use Abianbiya\Laralag\Modules\RoleUser\Models\RoleUser;

trait HasPermissions
{
    /**
     * The currently active role for the user.
     *
     * @var \App\Models\Role
     */
    protected $activeRole;

    /**
     * Relations
     *
     * @return void
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')->using(RoleUser::class)->withPivot('scope_id')->wherePivotNull('deleted_at');
    }

    public function roleUsers()
    {
        return $this->hasMany(RoleUser::class);
    }

    /**
     * Check if the user has a specific permission based on their active role.
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        if (session('active_role') === null){
            return false;
        }
        
        return in_array($permission, session('active_permissions'));
    }

    /**
     * Set the active role for the user.
     *
     * @param string $role
     * @param string|null $scope
     * @return void
     */
    public function setActiveRole($roleSlug, $scopeSlug = null)
    {
        $roleUser = $this->roleUsers()
            ->whereHas('role', function ($query) use ($roleSlug) {
                $query->where('slug', $roleSlug);
            })
            ->when($scopeSlug, function ($query) use ($scopeSlug) {
                $query->whereHas('scope', function ($q) use ($scopeSlug) {
                    $q->where('slug', $scopeSlug);
                });
            })
            ->firstOrFail();

        session([
            'active_role' => $roleUser->role->slug,
            'active_role_name' => $roleUser->role->nama,
            'active_scope' => $roleUser->scope ? $roleUser->scope->slug : null,
            'active_scope_name' => $roleUser->scope ? $roleUser->scope->akronim : null,
        ]);

        $this->syncPermission($roleUser->role->load('permission'));
    }

    public function syncPermission($role)
    {
        session(['active_permissions' => $role->permission->pluck('slug')->toArray()]);
    }
    
    /**
     * Get the currently active role for the user.
     *
     * @return \App\Models\Role|null
     */
    public function getActiveRole()
    {
        return session('active_role');
    }

    public function getActiveRoleName()
    {
        return session('active_role_name');
    }

    public function getActiveScope()
    {
        return session('active_scope');
    }

    public function getActiveScopeName()
    {
        return session('active_scope_name');
    }

    public function getActiveRoleScope()
    {
        return str(session('active_role_name'))->append(filled(session('active_scope')) ?  ' (' . session('active_scope_name').')' : '');
    }

    public function getRoleList(): array|null
    {
        return session('user_role_list');
    }

    public function setRoleList()
    {
        foreach($this->roleUsers as $roleUser){
            $roles[$roleUser->id]['display'] = str($roleUser->role->nama)->append($roleUser->scope_id ? ' ('. $roleUser->scope->akronim.')' : '');
            $roles[$roleUser->id]['role'] = $roleUser->role->slug;
            $roles[$roleUser->id]['scope'] = @$roleUser->scope->slug;
        }
        session(['user_role_list' => $roles]);
    }

    public function assignRole($roleSlug, $scopeSlug = null)
    {
        $role = Role::where('slug', $roleSlug)->firstOrFail();
        $scope = $scopeSlug ? Scope::where('slug', $scopeSlug)->firstOrFail() : null;

        RoleUser::create([
            'user_id' => $this->id,
            'role_id' => $role->id,
            'scope_id' => $scope ? $scope->id : null,
        ]);
    }

    public function revokeRole($roleSlug, $scopeSlug = null)
    {
        $this->roleUsers()
            ->whereHas('role', function ($query) use ($roleSlug) {
                $query->where('slug', $roleSlug);
            })
            ->when($scopeSlug, function ($query) use ($scopeSlug) {
                $query->whereHas('scope', function ($q) use ($scopeSlug) {
                    $q->where('slug', $scopeSlug);
                });
            })
            ->delete();
    }

    /**
     * Get the user's active role from the session.
     *
     * @return string|null
     */
    public function getActiveRoleAttribute()
    {
        return Session::get('active_role', 'blabla');
    }

    /**
     * Set the user's active role in the session.
     *
     * @param string $value
     * @return void
     */
    public function setActiveRoleAttribute($value)
    {
        Session::put('active_role', $value);
    }
}
