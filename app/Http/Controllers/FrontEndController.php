<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Admin\Menu;
use App\Model\Admin\SubMenu;
use App\Model\Admin\Header;
use App\Model\Admin\Slider;

class FrontEndController extends Controller
{
    
    public function index()
    { 
        $sliders = Slider::where('status', 'active')->orderBy('priority', 'ASC')->get()->all();
        $topmenu = Header::orderBy('idHeader', 'ASC')->get()->first();
        $menus = Menu::where('status', 'active')->orderBy('priority', 'ASC')->get()->all();
        $i=0;
        foreach ($menus as $menu) {
            $submenu = SubMenu::where('menuId', $menu->idMenus)->where('status', 'active')->orderBy('priority', 'ASC')->get()->all();
            $menus[$i]['submenus'] = $submenu;
            $i++;
        }
        
        return view('welcome',[ 
            'sliders'=>$sliders,
            'topmenu'=>$topmenu,
            'menus' => $menus
            ]);
    }

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
