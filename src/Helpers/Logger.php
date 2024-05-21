<?php
namespace Abianbiya\Laralag\Helpers;

use Illuminate\Http\Request;
use Abianbiya\Laralag\Modules\Log\Models\Log;

trait Logger{

	public function log(Request $request, $activity, $context = NULL, $data = NULL)
	{
		$user = $request->user();
		$route = $request->route()->getName();
		$elm = explode('.', $route);
		$action = end($elm);

		$log = $this->log;
		$log->user_id = @$user->id;
		$log->name = @$user->name ?? 'Guest';
		$log->aktivitas = $activity;
		$log->route = $route;
		$log->action = $action;
		$log->context = $context == null ? null : json_encode($context);
		$log->data_to = $data == null ? null : json_encode($data);
		$log->ip_address = $request->ip();
		$log->user_agent = $request->userAgent();
		$log->save();
	}

}