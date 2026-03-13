<?php

namespace Abianbiya\Laralag\Modules\PermissionRole\Models;

use Abianbiya\Laralag\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Abianbiya\Laralag\Modules\Permission\Models\Permission;
use Abianbiya\Laralag\Modules\Role\Models\Role;


class PermissionRole extends Model
{
	use SoftDeletes, HasAuditTrail;

	protected $table      = 'permission_role';
	public $incrementing  = false;
	protected $primaryKey = 'permission_id';
	protected $fillable   = ['permission_id','role_id'];



	public function permission(){
		return $this->belongsTo(Permission::class,"permission_id","id");
	}
public function role(){
		return $this->belongsTo(Role::class,"role_id","id");
	}

}
