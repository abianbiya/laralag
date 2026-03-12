<?php

namespace Abianbiya\Laralag\Modules\Permission\Models;

use Abianbiya\Laralag\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Permission extends Model
{
	use SoftDeletes, HasUuids, HasAuditTrail;

	protected $table      = 'permission';
	protected $fillable   = ['slug', 'nama', 'group', 'action'];


}
