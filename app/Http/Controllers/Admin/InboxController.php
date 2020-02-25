<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use App\User;
use DB;

class InboxController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.inbox');
    }

    public function contactUs(Request $request)
    {

        $this->validate($request, [
            'yname'         => 'required',
            'email'         => 'required|email',
            'description'   => 'required',
        ]);


        $now = date('Y-m-d H', strtotime(Carbon::now()));
        //check field
        $check = DB::table('notifications')->select('data','created_at')->where('read_at',null)->get();
            $remote_ip = CommonController::get_client_ip();
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $id = md5($user_agent.$remote_ip.$now);
        $error = 0;
        if($check->count() != 0){
            foreach($check as $row)
            {

                $data = json_decode($row->data);
                if($data->unique_id == $id){
                    $error++;
                }
                
            }   
        }

        //send to notification
        if($error == 0){

            $users = User::all();

            $details = [
                'name'          => $request->yname,
                'email'         => $request->email,
                'description'   => $request->description,
                'unique_id'     => $id,
            ];
            
            foreach ($users as $user) {
                
                $user->notify(new \App\Notifications\ContactUs($details));
            
            }
        }
        
        return redirect()->back();
    }

    public function show($id)
    {
        //
        $data = DB::table('notifications')->select('id','data','created_at','updated_at')->where('id',$id)->first();
        $arr = [];
        if($data){
            $row = json_decode($data->data);
            $arr = array(
                'id' =>$data->id, 
                'name' => $row->name, 
                'email' => $row->email,
                'data' => $row->description,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at 
            );
            
        }
        return json_encode($arr);
        
    }

    public function getData()
    {
        $data = DB::table('notifications')->select('id','data','created_at','updated_at')->orderBy('created_at', 'DESC')->get();
        
        $collection = collect();
        foreach($data as $row){
            $data = json_decode($row->data);
            $arr = array(
                'id' =>$row->id, 
                'name' => $data->name, 
                'email' => $data->email,
                'data' => $data->description,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at 
            );

            $collection->push($arr);
            
        }
            
        return Datatables::of($collection)->setTotalRecords($collection->count())
        ->editColumn('data', function($data) {
            return substr($data['data'],0,20).'...';
        })
        ->rawColumns(['data'])->make(true);
    
    }

    public function readNotif(User $user, $id)
    {
        $notif = $user->unreadNotifications()->where('id', $id)->first()->markAsRead();
        return response(['message'=>'done', 'notifications'=>$user->notifications]);
    }
    
    public function test()
    {
        $emails = config('email.emails');
        $collection = collect();
        foreach($emails as $row){
            $arr = array(
                'email' => $row,
            );
            $collection->push($arr);
        }
        
        $user = User::all();
        dd($user,$collection);
        
        $details = [
            'name'          => 'yyeyeye',
            'email'         => 'yyeyeye',
            'description'   => 'yyeyeye',
        ];
        foreach ($collection as $user) {
            dd($user);
            $user->notify(new \App\Notifications\ContactUs($details));
        }
        
        dd('done');

            
            //Alert()->success('Success')->autoclose(1500);
    }
}
