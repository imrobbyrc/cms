<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Admin\Menu;
use App\Model\Admin\SubMenu;
use App\Model\Admin\Content;
use App\Model\Admin\Header;
use App\Model\Admin\Footer;
use App\Model\Admin\Slider;
use App\Model\Admin\ContactUs;

class FrontEndController extends Controller
{
    
    public function index()
    { 
        $sliders = Slider::where('status', 'active')->orderBy('priority', 'ASC')->get()->all();
        $topmenu = Header::orderBy('idHeader', 'ASC')->get()->first();
        $botmenu = Footer::orderBy('idFooter', 'ASC')->take(4)->get();
        $menus = Menu::where('status', 'active')->where('showOnHomepage', 'yes')->orderBy('priority', 'ASC')->get()->all();
        $i=0;
        foreach ($menus as $menu) {
            $submenu = SubMenu::where('menuId', $menu->idMenus)->where('status', 'active')->orderBy('priority', 'ASC')->get()->all();
            $menus[$i]['submenus'] = $submenu;
                $x=0;
                foreach ($submenu as $sm) {
                    $content = Content::where('submenuId', $sm->idSubmenus)->where('status', 'active')->orderBy('priority', 'ASC')->get()->all();
                    $submenu[$x]['contents'] = $content;
                    $x++;
                }
            $i++;
        }
        
        return view('welcome',[ 
            'browserTitle'=>$topmenu->browserTitle,
            'metaDescription'=>$topmenu->metaDescription,
            'sliders'=>$sliders,
            'topmenu'=>$topmenu,
            'botmenu'=>$botmenu,
            'menus' => $menus
            ]);
    } 

    public function get($menu, $submenu = null, $content = null)
    {
        if (!is_null($content)) {
            $results = Content::where('link' , $content)->where('status', 'active')->get()->first();
            if(!is_null($results)){
                $results['related'] = Content::whereNotIn('idContents', [$results->idContents])->where('submenuId' , $results->submenuId)->where('status', 'active')->get()->all();
                $view = 'content';
            }else{
                abort(404);
            }
        }
        elseif (!is_null($submenu)) {
            $results = SubMenu::where('link' , $submenu)->where('status', 'active')->get()->first();
            if(!is_null($results)){
                $content = Content::where('submenuId', $results->idSubmenus)->where('status', 'active')->orderBy('priority', 'ASC')->get()->all();
                $results['contents'] = $content;
                $results['menus'] = Menu::where('idMenus', $results->menuId)->orderBy('idMenus', 'ASC')->get()->first();
                $view = 'submenu';
            }else{
                abort(404);
            }
        } else {
            if($menu === 'contact-us'){
                $results = ContactUs::where('idContacts' , 1)->get()->first();
                $view = 'contact-us';
            }else{
                $results = Menu::where('link' , $menu)->where('status', 'active')->get()->first(); 
                if(!is_null($results)){
                    $submenu = SubMenu::where('menuId', $results->idMenus)->where('status', 'active')->orderBy('priority', 'ASC')->get()->all();
                    $results['submenus'] = $submenu;
                    if($results->layout == 3){
                        $view = 'menu-2';
                    }else{
                        $view = 'menu';
                    }
                }else{
                    abort(404);
                }
            }
            
        } 

        $sliders = Slider::where('status', 'active')->orderBy('priority', 'ASC')->get()->all();
        $topmenu = Header::orderBy('idHeader', 'ASC')->get()->first();
        $botmenu = Footer::orderBy('idFooter', 'ASC')->take(4)->get();
        $menus = Menu::where('status', 'active')->where('showOnHomepage', 'yes')->orderBy('priority', 'ASC')->get()->all();
        $i=0;
        foreach ($menus as $menu) {
            $submenu = SubMenu::where('menuId', $menu->idMenus)->where('status', 'active')->orderBy('priority', 'ASC')->get()->all();
            $menus[$i]['submenus'] = $submenu;
                $x=0;
                foreach ($submenu as $sm) {
                    $content = Content::where('submenuId', $sm->idSubmenus)->where('status', 'active')->orderBy('priority', 'ASC')->get()->all();
                    $submenu[$x]['contents'] = $content;
                    $x++;
                }
            $i++;
        }
        // dd($results);
        return view($view,[ 
            'browserTitle'=>$topmenu->browserTitle,
            'metaDescription'=>$topmenu->metaDescription,
            'sliders'=>$sliders,
            'topmenu'=>$topmenu,
            'botmenu'=>$botmenu,
            'menus' => $menus,
            'results' => $results
            ]);
    }
}
