<?php

namespace Abianbiya\Laralag\Modules\Menu\Models;

use Abianbiya\Laralag\Traits\HasAuditTrail;
use Abianbiya\Laralag\Modules\Module\Models\Module;
use Illuminate\Database\Eloquent\Model;
use Abianbiya\Laralag\Modules\MenuGroup\Models\MenuGroup;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Menu extends Model
{
	use SoftDeletes, HasUuids, HasAuditTrail;

	protected $table      = 'menu';
	protected $fillable   = ['menu_group_id','nama','nama_en','icon','urutan','is_tampil'];



	public function menuGroup(){
		return $this->belongsTo(MenuGroup::class,"menu_group_id","id");
	}

	public function module()
	{
		return $this->hasMany(Module::class,"menu_id","id");
	}
	

}
