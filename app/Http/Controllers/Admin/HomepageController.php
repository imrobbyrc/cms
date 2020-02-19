<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Model\Admin\Slider;
use App\Model\Admin\Header;
use App\Model\Admin\Footer;
use DB;
use Alert;

class HomepageController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($alias)
    {
        if($alias == 'main-slider'){

            return view('admin.homepage.main-slider');
        }elseif ($alias == 'header-content') {

            return view('admin.homepage.header-content.index');
        }elseif ($alias == 'footer-content') {

            return view('admin.homepage.footer-content.index');
        }else{

             return abort(404);
        }
    }

    public function create($alias)
    {
        if($alias == 'main-slider'){

            return redirect()->back();

        }elseif ($alias == 'header-content') {

            return view('admin.homepage.header-content.create');

        }elseif ($alias == 'footer-content') {

            return view('admin.homepage.footer-content.create');
        }else{

             return abort(404);
        }
    }

    public function store(Request $request, $alias)
    {
        
        try 
        {

            if($alias == 'main-slider'){

                $this->validate($request, [
                    'title'	        => 'required',
                    'link'          => 'required',
                    'description'   => 'required',
                    'status'        => 'required',
                    'priority'      => 'required|numeric|unique:main_sliders',
                    'image'         => 'image|mimes:jpeg,png,jpg',
                ]);
    
                if($request->hasfile('image')) 
                { 
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename =time().'-'.$file->getClientOriginalName();
                    $file->move('image/slider/', $filename);
                }
    
                    $save = Slider::create([
    
                        'title'         => $request->title,
                        'link'          => $request->link,
                        'description'   => $request->description,
                        'status'        => $request->status,
                        'priority'      => $request->priority,
                        'image'         => 'image/slider/'. $filename,
        
                    ]);

    
            }elseif ($alias == 'header-content') {
    
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
    
                    $save = Header::create([
    
                        'browserIcon'       => 'image/icon/'. $filenameIcon,
                        'headerLogo'        => 'image/logo/'. $filenameLogo,
                        'contentLeft'       => $request->leftContent,
                        'contentRight'      => $request->rightContent,
                        'metaDescription'   => $request->descriptions,
                        'browserTitle'      => $request->title
        
                    ]);
    
    
            }elseif ($alias == 'footer-content') {
                
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
                return redirect()->route('homepage',['alias' => $alias]);

        }
            catch(\Illuminate\Database\QueryException $ex){ 
                Alert()->error('Failed')->autoclose(1500);
                    return redirect()->back();
        }

    }

    public function show($alias,$id)
    {
        if($alias == 'main-slider'){

            $data = Slider::findOrFail($id);
            return $data->toJson();

        }elseif ($alias == 'header-content') {

            
        }elseif ($alias == 'footer-content') {

           
        }else{

             return abort(404);
        }
    }

    public function edit($alias,$id)
    {

        if($alias == 'main-slider'){

            return redirect()->back();

        }elseif ($alias == 'header-content') {

            $data = Header::findOrFail($id);
            return view('admin.homepage.header-content.edit',['data'=>$data]);

        }elseif ($alias == 'footer-content') {

            $data = Footer::findOrFail($id);
            return view('admin.homepage.footer-content.edit',['data'=>$data]);
        }else{

             return abort(404);
        }
    }
    public function update(Request $request,$alias)
    {
        $id = $request->idUpdate;
        
        try 
        {

            if($alias == 'main-slider'){

            
                $filename = substr($request->currentImage,13);
                $this->validate($request, [
                    'title'	       => 'required',
                    'link'         => 'required',
                    'status'       => 'required',
                    'description'  => 'required',
                    'priority'     => 'required|numeric|unique:main_sliders,idMainSlider,'.$id.',idMainSlider',
                ]);
    
    
                if($request->hasfile('image')) 
                { 
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename =time().'-'.$file->getClientOriginalName();
                    $file->move('image/slider/', $filename);
                }
    
                    $save = Slider::where('idMainSlider',$id)->update([
    
                        'title'         => $request->title,
                        'link'          => $request->link,
                        'description'   => $request->description,
                        'status'        => $request->status,
                        'priority'      => $request->priority,
                        'image'         => 'image/slider/'. $filename,
        
                    ]);
                    
    
            }elseif ($alias == 'header-content') {
    
    
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
    
                
            }elseif ($alias == 'footer-content') {
    
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
                return redirect()->route('homepage',['alias' => $alias]);

        }

            catch(\Illuminate\Database\QueryException $ex){ 
                Alert()->error('Failed')->autoclose(1500);
                    return redirect()->back();
        }
        
    }

    public function destroy(Request $request,$alias)
    {
        $id = $request->id;
        if($alias == 'main-slider'){

            $data = Slider::findOrFail($id);

        }elseif ($alias == 'header-content') {

            $data = Header::findOrFail($id);
            
        }elseif ($alias == 'footer-content') {

            $data = Footer::findOrFail($id);
           
        }else{

             return abort(404);
        }
        
        $data->delete();

            Alert()->success('Success','Success')->autoclose(1500);
            return redirect()->back();
    }

    public function getData($alias)
    {
        if($alias == 'main-slider'){

            $data = Slider::orderBy('created_at', 'DESC')
                            ->orderBy('status', 'DESC');
                
            return Datatables::of($data)->setTotalRecords($data->count())
                    ->editColumn('status', function($data) {
                        $css = $data->status == 'active' ? 'badge badge-success':'badge badge-warning';
                        return '<div class="'.$css.'">'.ucfirst($data->status).'</div>';
                    })
                    ->editColumn('image', function($data) {
                        return '<img src="'.asset($data->image).'" width="100%">';
                    })->rawColumns(['status','image','description'])
                    ->make(true);
        
        }elseif ($alias == 'header-content') {

            $data = Header::orderBy('created_at', 'DESC');
                
            return Datatables::of($data)->setTotalRecords($data->count())
                    ->editColumn('browserIcon', function($data) {
                        return '<img src="'.asset($data->browserIcon).'" width="100%">';
                    })
                    ->editColumn('headerLogo', function($data) {
                        return '<img src="'.asset($data->headerLogo).'" width="100%">';
                    })->rawColumns(['browserIcon','headerLogo','contentLeft','contentRight','metaDescription'])
                    ->make(true);

        }elseif ($alias == 'footer-content') {

            $data = Footer::orderBy('created_at', 'DESC')
                            ->orderBy('status', 'DESC');
                
            return Datatables::of($data)->setTotalRecords($data->count())->editColumn('status', function($data) {
                    $css = $data->status == 'active' ? 'badge badge-success':'badge badge-warning';
                    return '<div class="'.$css.'">'.ucfirst($data->status).'</div>';
                })->rawColumns(['status','description'])->make(true);

        }else{

            return abort(404);
        }

    }
}
