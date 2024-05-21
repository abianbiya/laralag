<?php

namespace Abianbiya\Laralag\Modules\User\Models;

use Abianbiya\Laralag\Traits\HasPermissions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class User extends Model
{
	use SoftDeletes, HasUuids, HasPermissions;

	protected $table      = 'users';
	protected $fillable   = ['username','email','name','email_verified_at','password','identitas','remember_token'];

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

	
}
