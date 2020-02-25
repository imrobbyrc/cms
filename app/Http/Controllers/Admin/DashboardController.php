<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Artisan;


class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.home');
    }
    
    public function clear()
    {
          Artisan::call('cache:clear');
          Artisan::call('config:clear');
          Artisan::call('view:clear');
          Artisan::call('route:clear');
          
          dd('a');
    }
}
