<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    //
    protected $table= 'contacts';
    protected $primaryKey = 'idContacts';
    protected $guarded = [];
}
