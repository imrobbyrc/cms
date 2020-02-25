@extends('main')
@section('title', 'Welcome')

@section('stylesheet')
<style>
#primary{
    text-align:left;!important;
}
</style>
@endsection
	@section('content')   
        <div>
            <div class="background-holder overlay" style="{{$results->image}}"></div>
            <!--/.background-holder-->
            <div class="container">
                <div class="row pt-6" data-inertia='{"weight":1.5}'>
                    <div class="col-md-8 px-md-0 color-white" data-zanim-timeline="{}" data-zanim-trigger="scroll">
                        <div class="overflow-hidden">
                            <h1 class="color-white fs-4 fs-md-5 mb-0 zopacity" data-zanim='{"delay":0}'>{{$results->submenus}}</h1>
                            <div class="nav zopacity" aria-label="breadcrumb" role="navigation" data-zanim='{"delay":0.1}'>
                                <ol class="breadcrumb fs-1 pl-0 fw-700">
                                    <li class="breadcrumb-item"><a class="color-white" href="/">Home</a></li>
                                    <li class="breadcrumb-item"><a class="color-white" href="'/'{{$results->menus->link}}">{{$results->menus->menu}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{$results->submenus}}</li>
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
                <div class="col-9 col-lg-9 col-md-9 col-xl-9 col-sm-12 col-xs-12"> 
                    {!! $results->description !!}
                </div>
                <div class="col-3 col-lg-3 col-md-3 col-xl-3 col-sm-12 col-xs-12"> 
                    test
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                @foreach ($results->contents as $content) 
                <div class="col-sm-6 col-lg-4">
                    <a href="{{$results->link.'/'.$content->link}}">
                    <div class="background-white pb-4 h-100 radius-secondary">
                        <img class="mb-4 radius-tr-secondary radius-tl-secondary" src="{{$content->image}}" alt="Profile Picture" />
                        <div class="px-4" data-zanim-timeline="{}" data-zanim-trigger="scroll">
                            <div class="overflow-hidden">
                                <h5 data-zanim='{"delay":0}'>{{$content->submenu}}</h5></div>
                            <div class="overflow-hidden">
                                <h6 class="fw-400 color-7" data-zanim='{"delay":0.1}'>{{$content->title}}</h6></div>
                            <div class="overflow-hidden">
                                <p class="py-3 mb-0" data-zanim='{"delay":0.2}'>{!!$content->description!!}</p>
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