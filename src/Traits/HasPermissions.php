<?php

namespace Abianbiya\Laralag\Traits;

use Abianbiya\Laralag\Models\Permission;
use Abianbiya\Laralag\Modules\Role\Models\Role;
use Illuminate\Support\Facades\Session;

trait HasPermissions
{
    /**
     * The currently active role for the user.
     *
     * @var \App\Models\Role
     */
    protected $activeRole;

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
        // dd(session('active_permissions'));
        return in_array($permission, session('active_permissions'));
    }

    /**
     * Set the active role for the user.
     *
     * @param string $role
     * @param string|null $scope
     * @return void
     */
    public function setActiveRole($role, $scope = null)
    {
        $this->activeRole = $this->roles()->where('slug', $role)->first();
        session(['active_role' => $this->activeRole->slug]);
        session(['active_role_name' => $this->activeRole->nama]);
        session(['active_permissions' => $this->activeRole->permission->pluck('slug')->toArray()]);
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

    /**
     * Get the currently active role for the user.
     *
     * @return \App\Models\Role|null
     */
    public function getActiveRoleName()
    {
        return session('active_role_name');
    }

    /**
     * Get all roles assigned to the user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withPivot('scope_id');
    }

    /**
     * Assign a role to the user.
     *
     * @param string $role
     * @param string|null $scope
     * @return void
     */
    public function assignRole($role, $scope = null)
    {
        $role = Role::whereSlug($role)->firstOrFail();
        $this->roles()->attach($role, ['scope_id' => $scope]);
    }

    /**
     * Revoke a role from the user.
     *
     * @param string $role
     * @return void
     */
    public function revokeRole($role)
    {
        $role = Role::whereSlug($role)->firstOrFail();
        $this->roles()->detach($role);
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
