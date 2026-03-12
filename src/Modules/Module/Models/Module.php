<?php

namespace Abianbiya\Laralag\Modules\Module\Models;

use Abianbiya\Laralag\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Abianbiya\Laralag\Modules\Menu\Models\Menu;


class Module extends Model
{
	use SoftDeletes, HasUuids, HasAuditTrail;

	protected $table      = 'module';
	protected $fillable   = ['menu_id','nama','routing','permission','urutan','is_tampil'];



	public function menu(){
		return $this->belongsTo(Menu::class,"menu_id","id");
	}

}
