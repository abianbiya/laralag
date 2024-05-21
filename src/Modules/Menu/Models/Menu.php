<?php

namespace Abianbiya\Laralag\Modules\Menu\Models;

use Illuminate\Support\Facades\Auth;
use Abianbiya\Laralag\Modules\Module\Models\Module;
use Illuminate\Database\Eloquent\Model;
use Abianbiya\Laralag\Modules\MenuGroup\Models\MenuGroup;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Menu extends Model
{
	use SoftDeletes, HasUuids;

	protected $table      = 'menu';
	protected $fillable   = ['menu_group_id','nama','nama_en','icon','urutan','is_tampil'];

	public static function boot()
	{
		parent::boot();

		static::creating(function ($model) {
			$model->created_by = Auth::check() ? Auth::id() : 'Guest';
		});

		static::updating(function ($model) {
			$model->updated_by = Auth::check() ? Auth::id() : 'Guest';
		});

		static::deleting(function ($model) {
			$model->deleted_by = Auth::check() ? Auth::id() : 'Guest';
			$model->save();
		});

		static::restoring(function ($model) {
			$model->deleted_by = null;
			$model->save();
		});
	}

	public function menuGroup(){
		return $this->belongsTo(MenuGroup::class,"menu_group_id","id");
	}

	public function module()
	{
		return $this->hasMany(Module::class,"menu_id","id");
	}
	

}
