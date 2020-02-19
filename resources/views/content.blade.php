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
            <section class="background-11 ">
                <div class="container">
                    <div class="row">
                        <div class="col-12 mb-4">
                        <div class="overflow-hidden" data-zanim-timeline="{}" data-zanim-trigger="scroll">
                                <h4 data-zanim='{"delay":0.1}'>{{$results->title}}</h4></div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-12"><img class="radius-tr-secondary radius-tl-secondary" src="{{$results->image}}" alt=""></div>
                                <div class="col-12">
                                    <div class="background-white p-5 radius-secondary">
                                        {!!$results->description!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-center ml-auto mt-5 mt-lg-0">
                            <div class="row mt-5 px-2">
                                <div class="col">
                                    <h5 class="mb-3">Related Articles</h5>
                                    <div class="background-white pb-7 radius-secondary">
                                        <div class="owl-carousel owl-theme owl-nav-outer owl-dot-round mt-4" data-options='{"items":1}'>
                                            @foreach ($results->related as $related) 
                                            <div class="item">
                                                <div class="background-white pb-4 h-100 radius-secondary"><img class="w-100 radius-tr-secondary radius-tl-secondary" src="{{$related->image}}" alt="Featured Image">
                                                    <div class="px-4 pt-4"><a href="{{$related->link}}"><h5>{{$related->title}}</h5></a>
                                                        <p class="mt-3">{{$related->description}}</p><a href="{{$related->link}}">Learn More &xrarr;</a></div>
                                                </div>
                                            </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/.row-->
                </div>
                <!--/.container-->
            </section> 
@endsection