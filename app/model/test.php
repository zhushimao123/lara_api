<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class test extends Model
{
    public $timestamps = false;
    public $table = 't_test';
    protected  $primaryKey = 'uid';
}
