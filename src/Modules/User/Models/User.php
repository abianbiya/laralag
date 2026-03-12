<?php

namespace Abianbiya\Laralag\Modules\User\Models;

use Abianbiya\Laralag\Modules\RoleUser\Models\RoleUser;
use Abianbiya\Laralag\Traits\HasPermissions;
use Abianbiya\Laralag\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class User extends Model
{
	use SoftDeletes, HasUuids, HasPermissions, HasAuditTrail;

	protected $table      = 'users';
	protected $fillable   = ['username','email','name','email_verified_at','password','identitas','remember_token'];



	public function roleUser()
	{
		return $this->hasMany(RoleUser::class,'user_id','id');
	}
	
}
