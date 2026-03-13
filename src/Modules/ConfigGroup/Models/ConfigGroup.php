<?php

namespace Abianbiya\Laralag\Modules\ConfigGroup\Models;

use Abianbiya\Laralag\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Abianbiya\Laralag\Modules\Config\Models\Config;


class ConfigGroup extends Model
{
	use SoftDeletes, HasUuids, HasAuditTrail;

	protected $table      = 'config_group';
	protected $fillable   = ['slug', 'nama', 'urutan', 'icon', 'is_tampil'];



	public function configs()
	{
		return $this->hasMany(Config::class, 'config_group_id');
	}

}
