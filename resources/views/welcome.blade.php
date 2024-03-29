@extends('main')
@section('title', 'Welcome')

@section('stylesheet')
<style>
.navbar-elixir{
    margin-bottom:-8rem!important;
}
</style>
@endsection

	@section('content')  
        <div class="loading" id="preloader">
            <div class="loader h-100 d-flex align-items-center justify-content-center">
                <div class="line-scale">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
        <div class="flexslider flexslider-simple h-full loading">
            <ul class="slides">
                
                @foreach ($sliders as $slider)
                <li data-zanim-timeline="{}">
                    <section class="py-0">
                        <div>
                            <div class="background-holder elixir-zanimm-scale" style="background-image:url({{$slider->image}});" data-zanimm='{"from":{"opacity":0.1,"filter":"blur(10px)","scale":1.05},"to":{"opacity":1,"filter":"blur(0px)","scale":1}}'></div>
                            <!--/.background-holder-->
                            <div class="container">
                                <div class="row h-full py-8 align-items-center" data-inertia='{"weight":1.5}'>
                                    <div class="col-sm-8 col-lg-7 px-5 px-sm-3">
                                        <div class="overflow-hidden">
                                            <h1 class="fs-4 fs-md-5 zopacity" data-zanim='{"delay":0}'>{{$slider->title}}</h1></div>
                                        <div class="overflow-hidden">
                                            <p class="color-primary mt-4 mb-5 lh-2 fs-1 fs-md-2 zopacity" data-zanim='{"delay":0.1}'>{!!$slider->description!!}</p>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="zopacity" data-zanim='{"delay":0.2}'><a class="btn btn-primary mr-3 mt-3" href="{{$slider->link}}">Read more<span class="fa fa-chevron-right ml-2"></span></a><a class="btn btn-warning mt-3" href="contact-us">Contact us<span class="fa fa-chevron-right ml-2"></span></a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/.row-->
                        </div>
                        <!--/.container-->
                    </section>
                </li>
                @endforeach
            </ul>
        </div>

        @foreach ($menus as $content)
            @switch($content->layout)
            @case(1)
            <section class="background-11">
                <div class="container">
                    <h3 class="text-center fs-2 fs-md-3">{{$content->menu}}</h3>
                        <nav>
                            <div class="nav nav-tabs nav-fill">
                                @foreach ($content->submenus as $submenu)
                                    <a id="navLink-{{$submenu->idSubmenus}}" class="nav-item nav-link" onclick="changeTabs({{$submenu->idSubmenus}})">{{$submenu->title}}</a> 
                                @endforeach
                            </div>
                        </nav>
                        <div class="tab-content">
                            @foreach ($content->submenus as $submenu)
                            <div class="tab-pane" id="tabPane-{{$submenu->idSubmenus}}">
                                <div class="owl-carousel owl-theme owl-nav-outer owl-dot-round mt-3" data-options='{"items":3}'>
                                    <div class="item mx-2"><img class="radius-secondary" src="{{$submenu->image}}"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                </div>
            </section>
                @break
                @case(2)
                <section class="background-11">
                    <div class="container">
                        <h3 class="text-center fs-2 fs-md-3">{{$content->menu}}</h3>
                        <hr class="short" data-zanim='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll" />
                        
                        @foreach ($content->submenus as $submenu)
                        <div class="row no-gutters pos-relative mt-4 mt-lg-0">
                            <div class="elixir-caret d-none d-lg-block"></div>
                            <div class="col-lg-6 py-3 py-lg-0 mb-0" style="min-height:400px;">
                                <div class="background-holder radius-tl-secondary radius-tr-secondary radius-tr-lg-0 radius-tl-lg-0 radius-bl-0 radius-bl-lg-secondary" style="{{$submenu->image}}"></div>
                                <!--/.background-holder-->
                            </div>
                            <div class="col-lg-6 px-lg-5 py-lg-6 p-4 my-lg-0 background-white radius-bl-secondary radius-bl-lg-0 radius-br-secondary">
                                <div class="d-flex align-items-center h-100">
                                    <div data-zanim-timeline="{}" data-zanim-trigger="scroll">
                                        <div class="overflow-hidden">
                                            <h5 data-zanim='{"delay":0}'>{{$submenu->title}}</h5></div>
                                        <div class="overflow-hidden">
                                        <p class="mt-3" data-zanim='{"delay":0.1}'>{!!str_limit(strip_tags($submenu->description),200,'...')!!}</p>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div data-zanim='{"delay":0.2}'><a class="d-flex align-items-center" href="{{$content->link}}/{{$submenu->link}}">Learn More<div class="overflow-hidden ml-2"><span class="d-inline-block" data-zanim='{"from":{"opacity":0,"x":-30},"to":{"opacity":1,"x":0},"delay":0.8}'>&xrarr;</span></div></a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!--/.row-->
                    </div>
                    <!--/.container-->
                </section>
                @break
                @default
                <section>
                    <div class="container">
                        <div class="row mb-6">
                            <div class="col">
                                <h3 class="text-center fs-2 fs-lg-3">{{$content->menu}}</h3>
                                <hr class="short" data-zanim='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 pr-0 pr-lg-3"><img class="radius-secondary" src="{{$content->image}}" alt="" /></div>
                            <div class="col-lg-6 px-lg-5 mt-6 mt-lg-0" data-zanim-timeline="{}" data-zanim-trigger="scroll">

                                <div class="overflow-hidden">
                                    {!!$content->description!!}
                                </div>

                            </div>
                        </div>
                        <!--/.row-->
                    </div>
                    <!--/.container-->
                </section>
                <section class="text-center">
                    <div class="container">
                        <h3>{{$content->title}}</h3>
                        <hr class="short" data-zanim='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll" />
                        <div class="row">
                            
                            @foreach ($content->submenus as $submenu)
                            <div class="col-md-6 col-lg-4 mt-4" data-zanim-timeline="{}" data-zanim-trigger="scroll">
                                <div class="px-3 py-4 px-lg-4">
                                    <div class="overflow-hidden"><span class="icon-Double-Circle fs-3 color-primary fw-600" data-zanim='{"delay":0}'></span></div>
                                    <div class="overflow-hidden">
                                        <h5 class="mt-3" data-zanim='{"delay":0.1}'>{{$submenu->title}}</h5></div>
                                    <div class="overflow-hidden">
                                        <p class="mb-0" data-zanim='{"delay":0.2}'>{!!str_limit(strip_tags($submenu->description),100,'...')!!}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                        <!--/.row-->
                    </div>
                    <!--/.container-->
                </section>
            @endswitch
                <section class=" background-primary py-3">
                </section>
        @endforeach
        
        <section class=" background-white">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="owl-carousel owl-theme owl-dot-round" data-options='{"nav":true,"dots":false,"loop":true,"autoplay":true,"autoplayHoverPause":true,"items":1}'>
                            
                            @foreach ($testimonials as $testimonial)
                                <div class="row px-lg-8">
                                    <div class="col-4 col-md-3 mx-auto"><img class="radius-secondary mx-auto" src="{{$testimonial->image}}" alt="Member" style="width: auto" /></div>
                                    <div class="col-md-9 ml-auto mt-4 mt-md-0 px-4 px-sm-3">
                                        <p class="lead fw-400">{!!$testimonial->description!!}</p>
                                        <h6 class="fs-0 mb-1 mt-4">{{$testimonial->name}}</h6>
                                        <p class="mb-0 color-7">{{$testimonial->job}}</p>
                                    </div>
                                </div>
                            @endforeach
                            
                        </div>
                    </div>
                </div>
                <!--/.row-->
            </div>
            <!--/.container-->
        </section>
        <section class="background-10 py-6">
            <div class="container">
                <div class="row align-items-center" data-zanim-timeline="{}" data-zanim-trigger="scroll">

                    @foreach ($partnerships as $partnership)
                        <div class="col-4 col-md-2 my-3 overflow-hidden"><img src="{{$partnership->image}}" alt="" data-zanim="{}" /></div>
                    @endforeach
                    
                </div>
                <!--/.row-->
            </div>
            <!--/.container-->
        </section>
@endsection
@section('script')
<script>
    
    $(document).ready(function() {
        $('.nav-link').eq(0).addClass('active');
        $('.tab-pane').eq(0).addClass('fade show active');
    });
    function changeTabs(id){
        $('.nav-link').removeClass('active');
        $('.tab-pane').removeClass('fade show active');
        $('#tabPane-'+id).addClass('fade show active');
        $('#navLink-'+id).addClass('active');
    }
</script>
@endsection