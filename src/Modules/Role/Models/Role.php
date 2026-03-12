<?php

namespace Abianbiya\Laralag\Modules\Role\Models;


use Abianbiya\Laralag\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Abianbiya\Laralag\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Abianbiya\Laralag\Modules\RoleUser\Models\RoleUser;
use Abianbiya\Laralag\Modules\Permission\Models\Permission;


class Role extends Model
{
	use SoftDeletes, HasUuids, HasAuditTrail;

	protected $table      = 'role';
	protected $fillable   = ['slug','nama','tags'];



	public function permission()
	{
		return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
	}

	public function users()
	{
		return $this->belongsToMany(User::class, 'role_user')
					->using(RoleUser::class)
					->withPivot('scope_id');
	}
	
}
