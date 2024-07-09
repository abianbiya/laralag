<?php

namespace Abianbiya\Laralag\Modules\Role\Models;


use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Abianbiya\Laralag\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Abianbiya\Laralag\Modules\RoleUser\Models\RoleUser;
use Abianbiya\Laralag\Modules\Permission\Models\Permission;


class Role extends Model
{
	use SoftDeletes, HasUuids;

	protected $table      = 'role';
	protected $fillable   = ['slug','nama','tags'];

	public static function boot()
	{
		parent::boot();

		static::creating(function ($model) {
			$model->created_by = Auth::check() ? Auth::id() : 'Guest';
		});

		static::updating(function ($model) {
			$model->updated_by = Auth::check() ? Auth::id() : 'Guest';
		});

		static::deleting(function ($model) {
			$model->deleted_by = Auth::check() ? Auth::id() : 'Guest';
			$model->save();
		});

		static::restoring(function ($model) {
			$model->deleted_by = null;
			$model->save();
		});
	}

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
