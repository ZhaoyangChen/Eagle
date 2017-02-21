<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class Eagle_keyword extends Eloquent
{
    protected $primaryKey = '_id';
    protected $collection = 'Eagle_keyword';
}
