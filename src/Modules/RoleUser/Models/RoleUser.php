<?php
namespace Abianbiya\Laralag\Modules\RoleUser\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Abianbiya\Laralag\Modules\Role\Models\Role;
use Abianbiya\Laralag\Modules\User\Models\User;
use Abianbiya\Laralag\Modules\Scope\Models\Scope;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RoleUser extends Pivot
{
	use SoftDeletes, HasUuids;
	public $timestamps = false;
	protected $table      = 'role_user';
	protected $fillable   = ['role_id','user_id','scope_id'];

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
