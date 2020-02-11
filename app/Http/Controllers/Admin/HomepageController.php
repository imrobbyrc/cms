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

            return view('admin.homepage.header-content');
        }elseif ($alias == 'footer-content') {

            return view('admin.homepage.footer-content');
        }else{

             return abort(404);
        }
    }

    public function store(Request $request, $alias)
    {
        if($alias == 'main-slider'){

            $this->validate($request, [
                'title'	    => 'required',
                'link'      => 'required',
                'status'    => 'required',
                'priority'  => 'required|numeric|unique:main_sliders',
                'image'     => 'image|mimes:jpeg,png,jpg',
            ]);

            if($request->hasfile('image')) 
            { 
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename =time().'-'.$file->getClientOriginalName();
                $file->move('image/slider/', $filename);
            }

            try { 

                $save = Slider::create([

                    'title' => $request->title,
                    'link' => $request->link,
                    'status' => $request->status,
                    'priority'=>$request->priority,
                    'image' =>  'image/slider/'. $filename,
    
                ]);

                Alert()->success('Success','Added')->autoclose(1500);
                return redirect()->back();

            }catch(\Illuminate\Database\QueryException $ex){ 
                Alert()->error('Error','Failed')->autoclose(1500);
                return redirect()->back();
            }



        }elseif ($alias == 'header-content') {

            return view('admin.homepage.header-content');
        }elseif ($alias == 'footer-content') {

            return view('admin.homepage.footer-content');
        }else{

             return abort(404);
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

    public function update(Request $request,$alias)
    {
        $id = $request->idUpdate;
        

        if($alias == 'main-slider'){

            
            $filename = substr($request->currentImage,13);
            $this->validate($request, [
                'title'	    => 'required',
                'link'      => 'required',
                'status'    => 'required',
                'priority'  => 'required|numeric',
            ]);


            if($request->hasfile('image')) 
            { 
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename =time().'-'.$file->getClientOriginalName();
                $file->move('image/slider/', $filename);
            }

            
            try { 
          
                $save = Slider::where('idMainSlider',$id)->update([

                    'title' => $request->title,
                    'link' => $request->link,
                    'status' => $request->status,
                    'priority'=>$request->priority,
                    'image' =>  'image/slider/'. $filename,
    
                ]);

                Alert()->success('Success','Added')->autoclose(1500);
                return redirect()->back();

            }catch(\Illuminate\Database\QueryException $ex){ 
                Alert()->error('Error','Failed')->autoclose(1500);
                return redirect()->back();
            }
                

        }elseif ($alias == 'header-content') {

            
        }elseif ($alias == 'footer-content') {

           
        }else{

             return abort(404);
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
                })->rawColumns(['status','image'])
                ->make(true);
        
        }elseif ($alias == 'header-content') {

            $data = Header::orderBy('created_at', 'DESC');
                
            return Datatables::of($data)->setTotalRecords($data->count())->make(true);

        }elseif ($alias == 'footer-content') {

            $data = Footer::orderBy('created_at', 'DESC')
                            ->orderBy('status', 'DESC');
                
            return Datatables::of($data)->setTotalRecords($data->count())->editColumn('status', function($data) {
                    return '<div class="badge badge-success">'.ucfirst($data->status).'</div>';
                })->rawColumns(['id'])->make(true);

        }else{

            return abort(404);
        }

    }
}
