<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    public function get($alias){
        if ($alias == 'about-us') { 
            return view('user.about'); 
        }elseif ($alias == 'contact-us') { 
            return view('user.contact');
        }elseif ($alias == 'menu') { 
            return view('user.menu');
        }elseif ($alias == 'sub-menu') { 
            return view('user.submenu');
        }elseif ($alias == 'menu-2') { 
            return view('user.menu-2');
        }elseif ($alias == 'sub-menu-2') { 
            return view('user.submenu-2');
        }else{ 
             return abort(404);
        }
    }
}
