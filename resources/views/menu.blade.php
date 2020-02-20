@extends('main')
@section('title', 'Welcome')
    @section('content')   
    <section>
        <div>
            <div class="background-holder overlay" style="{{$results->image}}"></div>
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
    <section class="background-11 text-center">
        <div class="container">
            <div class="row">
                @foreach ($results->submenus as $submenu) 
                <div class="col-sm-6 col-lg-4">
                    <a href="{{$results->link.'/'.$submenu->link}}">
                    <div class="background-white pb-4 h-100 radius-secondary">
                        <img class="mb-4 radius-tr-secondary radius-tl-secondary" src="{{$submenu->image}}" alt="Profile Picture" />
                        <div class="px-4" data-zanim-timeline="{}" data-zanim-trigger="scroll">
                            <div class="overflow-hidden">
                                <h5 data-zanim='{"delay":0}'>{{$submenu->submenu}}</h5></div>
                            <div class="overflow-hidden">
                                <h6 class="fw-400 color-7" data-zanim='{"delay":0.1}'>{{$submenu->title}}</h6></div>
                            <div class="overflow-hidden">
                                <p class="py-3 mb-0" data-zanim='{"delay":0.2}'>{{$submenu->description}}</p>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                @endforeach

            </div>
            <!--/.row-->
        </div>
        <!--/.container-->
    </section>
@endsection