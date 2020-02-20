@extends('main')
@section('title', 'Welcome')
    @section('content') 
    <section>  
    <div>
        <div class="background-holder overlay" style="background-image:url(assets/images/background-15.jpg);""></div>
        <!--/.background-holder-->
        <div class="container">
            <div class="row pt-6" data-inertia='{"weight":1.5}'>
                <div class="col-md-8 px-md-0 color-white" data-zanim-timeline="{}" data-zanim-trigger="scroll">
                    <div class="overflow-hidden">
                        <h1 class="color-white fs-4 fs-md-5 mb-0 zopacity" data-zanim='{"delay":0}'>{{$results->menu}}</h1>
                        <div class="nav zopacity" aria-label="breadcrumb" role="navigation" data-zanim='{"delay":0.1}'>
                            <ol class="breadcrumb fs-1 pl-0 fw-700">
                                <li class="breadcrumb-item"><a class="color-white" href="/">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{$results->menu}}</li>
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
                    <div class="row no-gutters">
                        <div class="col-lg-4 py-3 py-lg-0" style="min-height:400px; background-position: top">
                            <div class="background-holder radius-tl-secondary radius-bl-lg-secondary radius-tr-secondary radius-tr-lg-0" style="background-image:url(assets/images/ceo.jpg);"></div>
                            <!--/.background-holder-->
                        </div>
                        <div class="col-lg-8 px-5 py-6 my-lg-0 background-white radius-tr-lg-secondary radius-br-secondary radius-bl-secondary radius-bl-lg-0">
                            <div class="d-flex align-items-center h-100">
                                <div data-zanim-timeline="{}" data-zanim-trigger="scroll">
                                    <h5 data-zanim='{"delay":0}'>{{$results->menu}}</h5>
                                <p class="my-4" data-zanim='{"delay":0.1}'>{{$results->title}}</p><img data-zanim='{"delay":0.2}' src="{{$results->image}}" alt="" />
                                  
                            </div>
                        </div>
                    </div>
                    <div class="row mt-6">
                        <div class="col">
                        <h3 class="text-center fs-2 fs-md-3">{{$results->menu}} Overview</h3>
                            <hr class="short" data-zanim='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll" />
                        </div>
                        <div class="col-12">
                            <div class="background-white px-3 mt-6 px-0 py-5 px-lg-5 radius-secondary">
                                {{$results->description}}
                            </div>
                        </div>
                    </div>
                    <!--/.row-->
                </div>
                <!--/.container-->
            </section>
        </div>
@endsection