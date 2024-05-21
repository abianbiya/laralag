<?php

namespace Abianbiya\Laralag\Modules\PermissionRole\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Abianbiya\Laralag\Modules\Permission\Models\Permission;
use Abianbiya\Laralag\Modules\Role\Models\Role;


class PermissionRole extends Model
{
	use SoftDeletes, HasUuids;

	protected $table      = 'permission_role';
	protected $fillable   = ['permission_id','role_id'];

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

	public function permission(){
		return $this->belongsTo(Permission::class,"permission_id","id");
	}
public function role(){
		return $this->belongsTo(Role::class,"role_id","id");
	}

}
