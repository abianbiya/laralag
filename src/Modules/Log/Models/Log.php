<?php

namespace Abianbiya\Laralag\Modules\Log\Models;

use Abianbiya\Laralag\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Abianbiya\Laralag\Modules\User\Models\User;


class Log extends Model
{
	use SoftDeletes, HasUuids, HasAuditTrail;

	protected $table      = 'log';
	protected $fillable   = ['user_id','name','aktivitas','route','action','context','data_from','data_to','ip_address','user_agent'];



	public function user(){
		return $this->belongsTo(User::class,"user_id","id");
	}

}
