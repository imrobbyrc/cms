<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


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

    public function inbox()
    {
        return view('admin.inbox');
    }

    // select2 searcj
	function ajax_get_all_submenu(){   
        $data = []; 
        $data[0] = array(
            "id" => 1,
            "text" => 'example',
            "html" => 'image'
        ); 
        $data[1] = array(
            "id" => 2,
            "text" => 'example2',
            "html" => 'image2'
        ); 

        return json_encode($data);
    }
}
