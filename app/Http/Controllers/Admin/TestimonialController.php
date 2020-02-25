<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Model\Admin\Testimonial;
use DB;
use Alert;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.testimonial.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try 
        {
            $this->validate($request, [
                'name'              => 'required',
                'job'               => 'required',
                'description'       => 'required',
                'link'              => 'required',
                'status'            => 'required',
                'image'             => 'image|mimes:jpeg,png,jpg|dimensions:min_width=200,min_height=200',
            ]);

            if($request->hasfile('image')) 
            { 
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filenameIcon =time().'-image-'.$file->getClientOriginalName();
                $file->move('image/testimonial/', $filenameIcon);
            }


                $save = Testimonial::create([

                    'image'             => 'image/testimonial/'. $filenameIcon,
                    'name'              => $request->name,
                    'job'               => $request->job,
                    'link'              => $request->link,
                    'description'       => $request->description,
                    'status'            => $request->status,

                ]);

            Alert()->success('Success')->autoclose(1500);
                return redirect()->route('testimonial.index');

        }
            catch(\Illuminate\Database\QueryException $ex){ 
                Alert()->error('Failed')->autoclose(1500);
                    return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $data = Testimonial::findOrFail($id);
        return $data->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $id = $request->idUpdate;

        try 
        {
                $filenameIcon = substr($request->currentImage,18);
                $this->validate($request, [

                    'name'              => 'required',
                    'job'               => 'required',
                    'description'       => 'required',
                    'link'              => 'required',
                    'status'            => 'required',
                    'image'             => 'image|mimes:jpeg,png,jpg|required_if:currentImage,null|dimensions:min_width=200,min_height=200',

                ]);

                if($request->hasfile('image')) 
                { 
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filenameIcon =time().'-image-'.$file->getClientOriginalName();
                    $file->move('image/testimonial/', $filenameIcon);
                }
    

                    $save = Testimonial::where('idTestimonial',$id)->update([
    
                        'image'             => 'image/testimonial/'. $filenameIcon,
                        'name'              => $request->name,
                        'job'               => $request->job,
                        'link'              => $request->link,
                        'description'       => $request->description,
                        'status'            => $request->status,
        
                    ]);
                    
    
            Alert()->success('Success')->autoclose(1500);
                return redirect()->route('testimonial.index');

        }

            catch(\Illuminate\Database\QueryException $ex){ 
                Alert()->error('Failed')->autoclose(1500);
                    return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;

        $data = Testimonial::findOrFail($id);
        
        $data->delete();

        Alert()->success('Success','Success')->autoclose(1500);
        return redirect()->back();
    }

    public function getData()
    {
        $data = Testimonial::orderBy('created_at', 'DESC')
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
    
    }
}
