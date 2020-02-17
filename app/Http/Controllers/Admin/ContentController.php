<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Model\Admin\Menu;
use App\Model\Admin\SubMenu;
use App\Model\Admin\Content;
use DB;
use Alert;

class ContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($alias)
    {
        if($alias == 'menu'){

            return view('admin.content.menu');
        }elseif ($alias == 'submenu') {

            $data = Menu::select('idMenus','menu')->latest()->get();
            return view('admin.content.submenu',['data' => $data]);
        }elseif ($alias == 'content') {

            return view('admin.content.content');

        }else{
             return abort(404);
        }
    }

    public function create($alias)
    {
        if($alias == 'menu'){

            return redirect()->back();

        }elseif ($alias == 'submenu') {

            return view('admin.homepage.submenu.create');

        }elseif ($alias == 'content') {

            return view('admin.homepage.content.create');
        }else{

             return abort(404);
        }
    }

    public function store(Request $request, $alias)
    {
        
        try 
        {

            if($alias == 'menu'){

                $this->validate($request, [
                    'menu'              => 'required',
                    'link'              => 'required',
                    'status'            => 'required',
                    'showOnHomepage'    => 'required',
                    'priority'          => 'required|numeric|unique:menus',
                    'browserTitle'      => 'required',
                    'metaDescription'   => 'required',
                ]);
    
                    $save = Menu::create([
    
                        'menu'              => $request->menu,
                        'link'              => $request->link,
                        'status'            => $request->status,
                        'showOnHomepage'    => $request->showOnHomepage,
                        'priority'          => $request->priority,
                        'browserTitle'      => $request->browserTitle,
                        'metaDescription'   => $request->metaDescription,

                    ]);

    
            }elseif ($alias == 'submenu') {
    
                $this->validate($request, [
                    'menuId'	        => 'required',
                    'submenus'          => 'required',
                    'title'             => 'required',
                    'description'       => 'required',
                    'link'              => 'required',
                    'status'            => 'required',
                    'layout'            => 'required',
                    'priority'          => 'required',
                    'imageIcon'         => 'image|mimes:jpeg,png,jpg',
                    'browserTitle'      => 'required',
                    'metaDescription'   => 'required',
                ]);
    
                if($request->hasfile('image')) 
                { 
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filenameIcon =time().'-image-'.$file->getClientOriginalName();
                    $file->move('image/submenu/', $filenameIcon);
                }
    
                    $save = SubMenu::create([
    
                        'image'             => 'image/submenu/'. $filenameIcon,
                        'menuId'	        => $request->menuId,
                        'submenus'          => $request->submenus,
                        'title'             => $request->title,
                        'description'       => $request->description,
                        'link'              => $request->link,
                        'status'            => $request->status,
                        'layout'            => $request->layout,
                        'priority'          => $request->priority,
                        'browserTitle'      => $request->browserTitle,
                        'metaDescription'   => $request->metaDescription,
        
                    ]);
    
    
            }elseif ($alias == 'content') {
                
                $this->validate($request, [
                    'title'	          => 'required',
                    'status'          => 'required',
                    'descriptions'    => 'required',
                    'priority'        => 'required|numeric|unique:footer_settings',
                ]);

                $save = Footer::create([
    
                    'title'         => $request->title,
                    'description'   => $request->descriptions,
                    'status'        => $request->status,
                    'priority'      => $request->priority,
    
                ]);                
                
                
            }else{
    
                 return abort(404);
            }

            Alert()->success('Success')->autoclose(1500);
                return redirect()->route('content',['alias' => $alias]);

        }
            catch(\Illuminate\Database\QueryException $ex){ 
                Alert()->error('Failed')->autoclose(1500);
                    return redirect()->back();
        }

    }

    public function show($alias,$id)
    {
        if($alias == 'menu'){

            $data = Menu::findOrFail($id);
            return $data->toJson();

        }elseif ($alias == 'submenu') {

            $data = SubMenu::findOrFail($id);
            return $data->toJson();
            
        }elseif ($alias == 'content') {

            $data = Content::findOrFail($id);
            return $data->toJson();
           
        }else{

             return abort(404);
        }
    }

    public function edit($alias,$id)
    {

        if($alias == 'menu'){

            return redirect()->back();

        }elseif ($alias == 'submenu') {

            $data = Header::findOrFail($id);
            return view('admin.homepage.submenu.edit',['data'=>$data]);

        }elseif ($alias == 'content') {

            $data = Footer::findOrFail($id);
            return view('admin.homepage.content.edit',['data'=>$data]);
        }else{

             return abort(404);
        }
    }
    public function update(Request $request,$alias)
    {
        $id = $request->idUpdate;
        
        try 
        {

            if($alias == 'menu'){

            
                $filename = substr($request->currentImage,13);
                $this->validate($request, [

                    'menu'              => 'required',
                    'link'              => 'required',
                    'status'            => 'required',
                    'showOnHomepage'    => 'required',
                    'browserTitle'      => 'required',
                    'metaDescription'   => 'required',
                    'priority'          => 'required|numeric|unique:menus,idMenus,'.$id.',idMenus',
                ]);
    

                    $save = Menu::where('idMenus',$id)->update([
    
                        'menu'              => $request->menu,
                        'link'              => $request->link,
                        'status'            => $request->status,
                        'showOnHomepage'    => $request->showOnHomepage,
                        'priority'          => $request->priority,
                        'browserTitle'      => $request->browserTitle,
                        'metaDescription'   => $request->metaDescription,
        
                    ]);
                    
    
            }elseif ($alias == 'submenu') {
    
    
                $filenameIcon = substr($request->currentbrowserIcon,11);
                $filenameLogo = substr($request->currentheaderLogo,11);
                $this->validate($request, [
                    'title'	        => 'required',
                    'rightContent'  => 'required',
                    'leftContent'   => 'required',
                    'descriptions'  => 'required',
                    'imageIcon'     => 'image|mimes:jpeg,png,jpg',
                    'imageLogo'     => 'image|mimes:jpeg,png,jpg',
                ]);
    
                if($request->hasfile('imageIcon')) 
                { 
                    $file = $request->file('imageIcon');
                    $extension = $file->getClientOriginalExtension();
                    $filenameIcon =time().'-ImageIcon-'.$file->getClientOriginalName();
                    $file->move('image/icon/', $filenameIcon);
                }
    
                if($request->hasfile('imageLogo')) 
                { 
                    $file = $request->file('imageLogo');
                    $extension = $file->getClientOriginalExtension();
                    $filenameLogo =time().'-imageLogo-'.$file->getClientOriginalName();
                    $file->move('image/logo/', $filenameLogo);
                }
    
                    $save = Header::where('idHeader',$id)->update([
    
                        'browserIcon'       => 'image/icon/'. $filenameIcon,
                        'headerLogo'        => 'image/logo/'. $filenameLogo,
                        'contentLeft'       => $request->leftContent,
                        'contentRight'      => $request->rightContent,
                        'metaDescription'   => $request->descriptions,
                        'browserTitle'      => $request->title
        
                    ]);
    
                
            }elseif ($alias == 'content') {
    
                $this->validate($request, [
                    'title'	          => 'required',
                    'status'          => 'required',
                    'descriptions'    => 'required',
                    'priority'        => 'required|numeric|unique:footer_settings,idFooter,'.$id.',idFooter',
                ]);

                $save = Footer::where('idFooter',$id)->update([
    
                    'title'         => $request->title,
                    'description'   => $request->descriptions,
                    'status'        => $request->status,
                    'priority'      => $request->priority,
    
                ]);      
               
            }else{
    
                 return abort(404);
            }

            Alert()->success('Success')->autoclose(1500);
                return redirect()->route('content',['alias' => $alias]);

        }

            catch(\Illuminate\Database\QueryException $ex){ 
                Alert()->error('Failed')->autoclose(1500);
                    return redirect()->back();
        }
        
    }

    public function destroy(Request $request,$alias)
    {
        $id = $request->id;
        if($alias == 'menu'){

            $data = Menu::findOrFail($id);

        }elseif ($alias == 'submenu') {

            $data = SubMenu::findOrFail($id);
            
        }elseif ($alias == 'content') {

            $data = Content::findOrFail($id);
           
        }else{

             return abort(404);
        }
        
        $data->delete();

            Alert()->success('Success','Success')->autoclose(1500);
            return redirect()->back();
    }

    public function getData($alias)
    {
        if($alias == 'menu'){

            $data = Menu::orderBy('created_at', 'DESC')
                            ->orderBy('status', 'DESC');
                
            return Datatables::of($data)->setTotalRecords($data->count())
                    ->editColumn('status', function($data) {
                        $css = $data->status == 'active' ? 'badge badge-success':'badge badge-warning';
                        return '<div class="'.$css.'">'.ucfirst($data->status).'</div>';
                    })
                    ->rawColumns(['status'])
                    ->make(true);
        
        }elseif ($alias == 'submenu') {

            $data = SubMenu::leftJoin('menus','submenus.menuId','menus.IdMenus')
            ->select('submenus.*','menus.menu')
            ->orderBy('created_at', 'DESC')
            ->orderBy('status', 'DESC');
                
            return Datatables::of($data)->setTotalRecords($data->count())
                    ->editColumn('image', function($data) {
                        return '<img src="'.asset($data->image).'" width="100%">';
                    })
                    ->editColumn('status', function($data) {
                        $css = $data->status == 'active' ? 'badge badge-success':'badge badge-warning';
                        return '<div class="'.$css.'">'.ucfirst($data->status).'</div>';
                    })->rawColumns(['image','status'])
                    ->make(true);

        }elseif ($alias == 'content') {

            $data = Content::orderBy('created_at', 'DESC')
                            ->orderBy('status', 'DESC');
                
            return Datatables::of($data)->setTotalRecords($data->count())->editColumn('status', function($data) {
                    $css = $data->status == 'active' ? 'badge badge-success':'badge badge-warning';
                    return '<div class="'.$css.'">'.ucfirst($data->status).'</div>';
                })->rawColumns(['status'])->make(true);

        }else{

            return abort(404);
        }

    }
}
