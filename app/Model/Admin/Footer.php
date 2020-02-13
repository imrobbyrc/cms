<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    //
    protected $table= 'footer_settings';
    protected $primaryKey = 'idFooter';
    protected $guarded = [];
}
