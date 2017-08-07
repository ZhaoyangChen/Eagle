<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;

class AjaxController extends BaseController
{
   public function update() {
		return Artisan::call('eagle:hunt');
	}
}
