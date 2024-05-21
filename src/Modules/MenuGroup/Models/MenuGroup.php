<?php

namespace Abianbiya\Laralag\Modules\MenuGroup\Models;

use Abianbiya\Laralag\Modules\Menu\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class MenuGroup extends Model
{
	use SoftDeletes, HasUuids;

	protected $table      = 'menu_group';
	protected $fillable   = ['nama','nama_en','urutan','is_tampil'];

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

	public function menu()
	{
		return $this->hasMany(Menu::class,"menu_group_id","id");
	}
	
}
