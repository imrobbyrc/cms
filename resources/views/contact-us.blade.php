@extends('main')
@section('title', 'Welcome')
	@section('content')   
        <section>
            <div>
                <div class="background-holder overlay" style="background-image:url(assets/images/background-2.jpg);background-position: center bottom;"></div>
                <!--/.background-holder-->
                <div class="container">
                    <div class="row pt-6" data-inertia='{"weight":1.5}'>
                        <div class="col-md-8 px-md-0 color-white" data-zanim-timeline="{}" data-zanim-trigger="scroll">
                            <div class="overflow-hidden">
                                <h1 class="color-white fs-4 fs-md-5 mb-0 zopacity" data-zanim='{"delay":0}'>Contact</h1>
                                <div class="nav zopacity" aria-label="breadcrumb" role="navigation" data-zanim='{"delay":0.1}'>
                                    <ol class="breadcrumb fs-1 pl-0 fw-700">
                                        <li class="breadcrumb-item"><a class="color-white" href="/">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Contact </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/.row-->
            </div>
            <!--/.container-->
        </section>
        <section class="background-11">
            <div class="container">
                <div class="row align-items-stretch justify-content-center">
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="h-100 px-5 py-4 background-white radius-secondary">
                            <h5 class="mb-3">{{$results->serviceTittle1}}</h5>
                            {!!$results->serviceDescription1!!}}
                    </div>
                </div>
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="h-100 px-5 py-4 background-white radius-secondary">
                            <h5 class="mb-3">{{$results->serviceTittle2}}</h5>
                            {!!$results->serviceDescription2!!}}
                    </div>
                </div>
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="h-100 px-5 py-4 background-white radius-secondary">
                            <h5 class="mb-3">{{$results->serviceTittle3}}</h5>
                            {!!$results->serviceDescription3!!}}
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="background-white p-5 radius-secondary">
                            <div class="row">
                                <div class="col-6">
                                    {!!$results->contact!!}
                                </div>
                                <div class="col-6">
                                    <div class="mapouter">
                                        <div class="gmap_canvas">
                                            <iframe width="100%" height="400px" id="gmap_canvas" src="https://maps.google.com/maps?q=.{{$results->fullAddress ?? 'monas'}}.&t=&z=19&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
                                            </iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="background-white p-5 h-100 radius-secondary">
                            <h5>Write to us</h5>
                            <form method="post" action="{{ route('contact.store')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <input class="form-control" type="hidden" name="to" value="user@domain.extension">
                                        <input class="form-control background-white" type="text" placeholder="Your Name" name="yname" required>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <input class="form-control background-white" type="email" placeholder="Email" name="email" required>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <textarea class="form-control background-white" rows="11" placeholder="Enter your descriptions here..." name="description" required></textarea>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <div class="row">
                                            <div class="col-auto">
                                                <button class="btn btn-md-lg btn-primary" type="Submit"> <span class="color-white fw-600">Send Now</span></button>
                                            </div>
                                            <div class="col">
                                                <div class="zform-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--/.row-->
            </div>
            <!--/.container-->
        </section> 
@endsection