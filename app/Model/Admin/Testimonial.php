<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    //
    protected $table= 'testimonial';
    protected $primaryKey = 'idTestimonial';
    protected $guarded = [];
}
