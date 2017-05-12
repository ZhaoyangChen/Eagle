<?php

namespace App\Http\Controllers;

use App\Eagle_index;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IndexListController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function index() {
        $stamps = $this->getStamps();
        $indexs = $this->getIndex();
        return view('indexlist', ['stamps' => $stamps, 'indexs' => $indexs]);
    }

    protected function getStamps() {
        $res = [];
        $time = strtotime(date('Y-m-d'));
        for($i = 0; $i < 30; $i++) {
            $res[] = $time - 86400 * $i;
        }
        return $res;
    }

    protected function getIndex() {
        return Eagle_index::all();
    }

}
