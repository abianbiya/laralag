<?php

namespace Abianbiya\Laralag\Modules\MenuGroup\Models;

use Abianbiya\Laralag\Modules\Menu\Models\Menu;
use Abianbiya\Laralag\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class MenuGroup extends Model
{
	use SoftDeletes, HasUuids, HasAuditTrail;

	protected $table      = 'menu_group';
	protected $fillable   = ['nama','nama_en','urutan','is_tampil'];



	public function menu()
	{
		return $this->hasMany(Menu::class,"menu_group_id","id")->orderBy('urutan');
	}
	
}
