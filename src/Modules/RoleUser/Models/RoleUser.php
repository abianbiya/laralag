<?php

namespace Abianbiya\Laralag\Modules\RoleUser\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Abianbiya\Laralag\Modules\Role\Models\Role;
use Abianbiya\Laralag\Modules\User\Models\User;
use Abianbiya\Laralag\Modules\Scope\Models\Scope;


class RoleUser extends Model
{
	use SoftDeletes, HasUuids;
	protected $table      = 'role_user';
	protected $fillable   = ['role_id','user_id','scope_id'];

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

		// static::restoring(function ($model) {
		// 	$model->deleted_by = null;
		// 	$model->save();
		// });
	}

	public function role(){
		return $this->belongsTo(Role::class,"role_id","id");
	}
	public function user(){
		return $this->belongsTo(User::class,"user_id","id");
	}
	public function scope(){
		return $this->belongsTo(Scope::class,"scope_id","id");
	}

}
