<?php

namespace Abianbiya\Laralag\Modules\Scope\Models;

use Abianbiya\Laralag\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Scope extends Model
{
	use SoftDeletes, HasUuids, HasAuditTrail;

	protected $table      = 'scope';
	protected $fillable   = ['slug','nama','akronim','kode'];



	
}
