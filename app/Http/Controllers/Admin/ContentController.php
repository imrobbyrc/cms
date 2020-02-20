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

            $data = Menu::select('idMenus','menu')->where('status','active')->latest()->get();
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
                    'title'             => 'required',
                    'description'       => 'required',
                    'link'              => 'required',
                    'status'            => 'required',
                    'showOnHomepage'    => 'required',
                    'priority'          => 'required|numeric|unique:menus',
                    'image'             => 'image|mimes:jpeg,png,jpg|dimensions:min_width=478,min_height=477|max:45',
                    'browserTitle'      => 'required',
                    'metaDescription'   => 'required',
                    'layout'            => 'required',
                ]);

                if($request->hasfile('image')) 
                { 
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filenameIcon =time().'-image-'.$file->getClientOriginalName();
                    $file->move('image/menu/', $filenameIcon);
                }

    
                    $save = Menu::create([

                        'image'             => 'image/menu/'. $filenameIcon,
                        'menu'              => $request->menu,
                        'title'             => $request->title,
                        'description'       => $request->description,
                        'link'              => $request->link,
                        'status'            => $request->status,
                        'layout'            => $request->layout,
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
                    'image'             => 'image|mimes:jpeg,png,jpg|dimensions:min_width=478,min_height=477|max:45',
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
                    'submenuId'         => 'required',
                    'contents'          => 'required',
                    'title'             => 'required',
                    'description'       => 'required',
                    'link'              => 'required',
                    'image'             => 'image|mimes:jpeg,png,jpg|dimensions:min_width=478,min_height=477|max:45',
                    'status'            => 'required',
                    'priority'          => 'required|numeric|unique:contents',
                    'browserTitle'      => 'required',
                    'metaDescription'   => 'required',
                ]);


                if($request->hasfile('image')) 
                { 
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filenameIcon =time().'-image-'.$file->getClientOriginalName();
                    $file->move('image/content/', $filenameIcon);
                }

                $save = Content::create([
                    'submenuId'         => $request->submenuId,
                    'contents'          => $request->contents,
                    'title'             => $request->title,
                    'description'       => $request->description,
                    'link'              => $request->link,
                    'image'             => 'image/content/'. $filenameIcon,
                    'status'            => $request->status,
                    'priority'          => $request->priority,
                    'browserTitle'      => $request->browserTitle,
                    'metaDescription'   => $request->metaDescription,

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

            $data = Content::leftJoin('submenus','contents.submenuId','submenus.idSubmenus')
            ->select('contents.*','submenus.submenus')->where('idContents',$id)->first();
            return $data->toJson();
           
        }else{

             return abort(404);
        }
    }

    public function edit($alias,$id)
    {

        return abort(404);
        // if($alias == 'menu'){

        //     return redirect()->back();

        // }elseif ($alias == 'submenu') {

        //     $data = Header::findOrFail($id);
        //     return view('admin.homepage.submenu.edit',['data'=>$data]);

        // }elseif ($alias == 'content') {

        //     $data = Footer::findOrFail($id);
        //     return view('admin.homepage.content.edit',['data'=>$data]);
        // }else{

        //      return abort(404);
        // }
    }
    public function update(Request $request,$alias)
    {
        $id = $request->idUpdate;
        
        try 
        {

            if($alias == 'menu'){

                $filenameIcon = substr($request->currentImage,11);
                $this->validate($request, [

                    'menu'              => 'required',
                    'title'             => 'required',
                    'description'       => 'required',
                    'link'              => 'required',
                    'status'            => 'required',
                    'layout'            => 'required',
                    'showOnHomepage'    => 'required',
                    'browserTitle'      => 'required',
                    'metaDescription'   => 'required',
                    'priority'          => 'required|numeric|unique:menus,idMenus,'.$id.',idMenus',
                    'image'             => 'image|mimes:jpeg,png,jpg|required_if:currentImage,null|dimensions:min_width=478,min_height=477|max:45',

                ]);

                if($request->hasfile('image')) 
                { 
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filenameIcon =time().'-image-'.$file->getClientOriginalName();
                    $file->move('image/menu/', $filenameIcon);
                }
    

                    $save = Menu::where('idMenus',$id)->update([
    
                        'image'             => 'image/menu/'. $filenameIcon,
                        'menu'              => $request->menu,
                        'title'             => $request->title,
                        'description'       => $request->description,
                        'link'              => $request->link,
                        'status'            => $request->status,
                        'layout'            => $request->layout,
                        'showOnHomepage'    => $request->showOnHomepage,
                        'priority'          => $request->priority,
                        'browserTitle'      => $request->browserTitle,
                        'metaDescription'   => $request->metaDescription,
        
                    ]);
                    
    
            }elseif ($alias == 'submenu') {
    
                
                $filenameIcon = substr($request->currentImage,14);
                $this->validate($request, [

                    'menuId'	        => 'required',
                    'submenus'          => 'required',
                    'title'             => 'required',
                    'description'       => 'required',
                    'link'              => 'required',
                    'status'            => 'required',
                    'layout'            => 'required',
                    'priority'          => 'required',
                    'image'             => 'image|mimes:jpeg,png,jpg|required_if:currentImage,null|dimensions:min_width=478,min_height=477|max:45',
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
                
    
                    $save = SubMenu::where('idSubmenus',$id)->update([

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
    
                
                $filenameIcon = substr($request->currentImage,14);


                $this->validate($request, [

                    'submenuId'         => 'required',
                    'contents'          => 'required',
                    'title'             => 'required',
                    'description'       => 'required',
                    'link'              => 'required',
                    'image'             => 'image|mimes:jpeg,png,jpg|required_if:currentImage,null|dimensions:min_width=478,min_height=477|max:45',
                    'status'            => 'required',
                    'priority'          => 'required|numeric|unique:contents,idContents,'.$id.',idContents',
                    'browserTitle'      => 'required',
                    'metaDescription'   => 'required',

                ]);

                if($request->hasfile('image')) 
                { 
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filenameIcon =time().'-image-'.$file->getClientOriginalName();
                    $file->move('image/content/', $filenameIcon);
                }

                $save = Content::where('idContents',$id)->update([
    
                    'submenuId'         => $request->submenuId,
                    'contents'          => $request->contents,
                    'title'             => $request->title,
                    'description'       => $request->description,
                    'link'              => $request->link,
                    'image'             => 'image/content/'. $filenameIcon,
                    'status'            => $request->status,
                    'priority'          => $request->priority,
                    'browserTitle'      => $request->browserTitle,
                    'metaDescription'   => $request->metaDescription,
    
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
                    ->editColumn('image', function($data) {
                        return '<img src="'.asset($data->image).'" width="100%">';
                    })
                    ->rawColumns(['status','image','description'])
                    ->make(true);
        
        }elseif ($alias == 'submenu') {

            $data = SubMenu::leftJoin('menus','submenus.menuId','menus.idMenus')
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
                    })->rawColumns(['image','status','description'])
                    ->make(true);

        }elseif ($alias == 'content') {

            $data = Content::leftJoin('submenus','contents.submenuId','submenus.idSubmenus')
            ->select('contents.*','submenus.submenus')
            ->orderBy('created_at', 'DESC')
            ->orderBy('status', 'DESC');
                
            return Datatables::of($data)->setTotalRecords($data->count())
                    ->editColumn('image', function($data) {
                        return '<img src="'.asset($data->image).'" width="100%">';
                    })
                    ->editColumn('status', function($data) {
                        $css = $data->status == 'active' ? 'badge badge-success':'badge badge-warning';
                        return '<div class="'.$css.'">'.ucfirst($data->status).'</div>';
                    })->rawColumns(['image','status','description'])
                    ->make(true);

        }else{

            return abort(404);
        }

    }

    public function ajax_get_all_submenu(Request $request)
    {
        $q = $request->q;
        if($q == null){
            
            return 'No Data';

        }else{
            $data = SubMenu::select('idSubmenus as id','submenus as text','image as html');
            $data->where('status','active')->where(function ($query){
                $query->where('idSubmenus','like', '%'.$q.'%')
                ->orWhere('submenus','like', '%'.$q.'%');
            });

            $data = $data->get();

            return $data->toJson();
        }
            
    }
}
