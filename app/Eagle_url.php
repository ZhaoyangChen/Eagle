<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class Eagle_url extends Eloquent
{
    protected $primaryKey = '_id';
    protected $collection = 'Eagle_url';
}
