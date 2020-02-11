<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table= 'main_sliders';
    protected $primaryKey = 'idMainSlider';
    protected $guarded = [];
}
