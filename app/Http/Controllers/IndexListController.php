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
        for($i = 1; $i <= 30; $i++) {
            $res[] = strtotime("-{$i} day");
        }
        return $res;
    }

    protected function getIndex() {
        $res = [];
        $data =  Eagle_index::all();
        $obj = [];
        foreach ($data as $index) {
            foreach ($index->indexs as $i) {
                $obj[$i['ctime']] = intval($i['total']);
            }
            $res[] = $obj;
        }
        return $res;
    }

}
