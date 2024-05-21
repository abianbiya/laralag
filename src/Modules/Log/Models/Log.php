<?php

namespace Abianbiya\Laralag\Modules\Log\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Abianbiya\Laralag\Modules\User\Models\User;


class Log extends Model
{
	use SoftDeletes, HasUuids;

	protected $table      = 'log';
	protected $fillable   = ['user_id','name','aktivitas','route','action','context','data_from','data_to','ip_address','user_agent'];

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

	public function user(){
		return $this->belongsTo(User::class,"user_id","id");
	}

}
