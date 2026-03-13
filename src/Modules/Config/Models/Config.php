<?php

namespace Abianbiya\Laralag\Modules\Config\Models;

use Abianbiya\Laralag\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Abianbiya\Laralag\Modules\ConfigGroup\Models\ConfigGroup;


class Config extends Model
{
	use SoftDeletes, HasUuids, HasAuditTrail;

	protected $table    = 'config';
	protected $fillable = [
		'config_group_id',
		'config_name',
		'key',
		'default_value',
		'current_value',
		'form_type',
		'form_options',
		'is_multiple',
		'form_label',
		'form_placeholder',
		'form_help',
		'validation_rules',
		'urutan',
		'is_tampil',
	];

	protected $casts = [
		'form_options' => 'array',
	];



	public function configGroup()
	{
		return $this->belongsTo(ConfigGroup::class, 'config_group_id');
	}

	public function getValueAttribute()
	{
		return $this->current_value ?? $this->default_value;
	}

	public function scopeVisible($query)
	{
		return $query->where('is_tampil', 1)->orderBy('urutan');
	}

	public function scopeByGroup($query, $groupSlug)
	{
		return $query->whereHas('configGroup', fn($q) => $q->where('slug', $groupSlug));
	}

}
