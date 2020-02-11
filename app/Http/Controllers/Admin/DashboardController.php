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

    public function homepage($alias)
    {
        if($alias == 'main-slider'){
            return view('admin.homepage.main-slider');
        }elseif ($alias == 'header-content') {
            return view('admin.homepage.header-content');
        }elseif ($alias == 'footer-content') {
            return view('admin.homepage.footer-content');
        }else{
             return abort(404);
        }
    }

    public function contact($alias)
    {
        if($alias == 'contact-us'){
            return view('admin.contact-us.contact-us');
        }elseif ($alias == 'location') {
            return view('admin.contact-us.location');
        }else{
             return abort(404);
        }
    }

    public function content($alias)
    {
        if($alias == 'menu'){
            return view('admin.content.menu');
        }elseif ($alias == 'submenu') {
            return view('admin.content.submenu');
        }elseif ($alias == 'content') {
            return view('admin.content.content');
        }else{
             return abort(404);
        }
    }

    public function inbox()
    {
        return view('admin.inbox');
    }
}
