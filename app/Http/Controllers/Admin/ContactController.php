<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Admin\ContactUs;

class ContactController extends Controller
{
    //
    
    public function index($alias)
    {
        $data = ContactUs::first();
        if($alias == 'contact-us'){
            
            return view('admin.contact-us.contact-us',['data'=>$data]);

        }elseif ($alias == 'location') {

            return view('admin.contact-us.location',['data'=>$data]);

        }else{
             return abort(404);
        }
    }

    public function store(Request $request, $alias)
    {

      $data = ContactUs::select('idContacts')->first();

            if($alias == 'contact-us'){

                $this->validate($request, [
                    'contact'	        => 'required',
                    'showOnHomepage'    => 'required',
                    'metaDescription'   => 'required',
                    'browserTitle'      => 'required',
                ]);

                

                if($data == null){

                    $save = ContactUs::create([
                        'fullAddress' => "",
                        'contact' => $request->contact,
                        'serviceDescription1' => $request->serviceDescription1,
                        'serviceTittle1'  => $request->serviceTitle1,
                        'serviceDescription2' => $request->serviceDescription2,
                        'serviceTittle2' => $request->serviceTitle2,
                        'serviceDescription3' => $request->serviceDescription3,
                        'serviceTittle3' => $request->serviceTitle3,
                        'showOnHomepage' => $request->showOnHomepage,
                        'browserTitle' => $request->browserTitle,
                        'metaDescription' => $request->metaDescription,

                    ]);

                    Alert()->success('Success')->autoclose(1500);
                    return redirect()->back();

                }else{

                    $save = ContactUs::where('idContacts',$data->idContacts)->update([
    
                        'contact' => $request->contact,
                        'serviceDescription1' => $request->serviceDescription1,
                        'serviceTittle1'  => $request->serviceTitle1,
                        'serviceDescription2' => $request->serviceDescription2,
                        'serviceTittle2' => $request->serviceTitle2,
                        'serviceDescription3' => $request->serviceDescription3,
                        'serviceTittle3' => $request->serviceTitle3,
                        'showOnHomepage' => $request->showOnHomepage,
                        'browserTitle' => $request->browserTitle,
                        'metaDescription' => $request->metaDescription,
        
                    ]);

                    Alert()->success('Success')->autoclose(1500);
                    return redirect()->back();
                    
                }

            
            }elseif($alias == 'location') {

                if($data == null){

                    $save = ContactUs::create([

                        
                        'fullAddress' => $request->fullAddress,
                        'contact' => '',
                        'showOnHomepage' => "no",
                        'browserTitle' => '',
                        'metaDescription' => '',

                    ]);

                }else{

                    $save = ContactUs::where('idContacts',$data->idContacts)->update([
                        
                        'fullAddress' => $request->fullAddress

                    ]);

                }
            }

        
    }
}
