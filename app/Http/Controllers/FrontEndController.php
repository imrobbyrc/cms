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
use App\Model\Admin\Partnership;
use App\Model\Admin\Testimonial;

class FrontEndController extends Controller
{
    
    public function index()
    { 
        $partnetships = Partnership::where('status', 'active')->orderBy('idPartership', 'ASC')->get()->all();
        $testimonials = Testimonial::where('status', 'active')->orderBy('idTestimonial', 'ASC')->get()->all();
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
            'partnerships'=>$partnetships,
            'testimonials'=>$testimonials,
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
                $browserTitle = $results->browserTitle;
                $metaDescription = $results->brometaDescriptionwserTitle;
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
                $browserTitle = $results->browserTitle;
                $metaDescription = $results->brometaDescriptionwserTitle;
            }else{
                abort(404);
            }
        } else {
            if($menu === 'contact-us'){
                $results = ContactUs::where('idContacts' , 1)->get()->first();
                $view = 'contact-us';
                $browserTitle = $results->browserTitle;
                $metaDescription = $results->brometaDescriptionwserTitle;
            }else{
                $results = Menu::where('link' , $menu)->where('status', 'active')->get()->first(); 
                if(!is_null($results)){
                    $submenu = SubMenu::where('menuId', $results->idMenus)->where('status', 'active')->orderBy('priority', 'ASC')->get()->all();
                    $results['submenus'] = $submenu;
                    $browserTitle = $results->browserTitle;
                    $metaDescription = $results->brometaDescriptionwserTitle;
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
            'browserTitle'=>$browserTitle,
            'metaDescription'=>$metaDescription,
            'sliders'=>$sliders,
            'topmenu'=>$topmenu,
            'botmenu'=>$botmenu,
            'menus' => $menus,
            'results' => $results
            ]);
    }
}
