@extends('main')
@section('title', 'Welcome')
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
                                            <p class="color-primary mt-4 mb-5 lh-2 fs-1 fs-md-2 zopacity" data-zanim='{"delay":0.1}'>{{$slider->description}}</p>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="zopacity" data-zanim='{"delay":0.2}'><a class="btn btn-primary mr-3 mt-3" href="{{$slider->link}}">Read more<span class="fa fa-chevron-right ml-2"></span></a><a class="btn btn-warning mt-3" href="contact.html">Contact us<span class="fa fa-chevron-right ml-2"></span></a></div>
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
                        <hr class="short" data-zanim='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll" />
                        <div class="row no-gutters pos-relative mt-6">
                            <div class="elixir-caret d-none d-lg-block"></div>
                            <div class="col-lg-6 py-3 py-lg-0 mb-0" style="min-height:400px;">
                                <div class="background-holder radius-tl-secondary radius-tr-secondary radius-tr-lg-0" style="background-image:url(assets/images/6.jpg);"></div>
                                <!--/.background-holder-->
                            </div>
                            <div class="col-lg-6 px-lg-5 py-lg-6 p-4 my-lg-0 background-white radius-bl-secondary radius-bl-lg-0 radius-br-secondary radius-br-lg-0 radius-tr-lg-secondary">
                                <div class="d-flex align-items-center h-100">
                                    <div data-zanim-timeline="{}" data-zanim-trigger="scroll">
                                        <div class="overflow-hidden">
                                            <h5 data-zanim='{"delay":0}'>Business Consulting</h5></div>
                                        <div class="overflow-hidden">
                                            <p class="mt-3" data-zanim='{"delay":0.1}'>As one of the world’s largest accountancy networks, elixir helps a diverse range of clients with a diverse range of needs.This is especially true of our Advisory Practice, which provides corporate finance and transaction services, business restructuring.</p>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div data-zanim='{"delay":0.2}'><a class="d-flex align-items-center" href="#">Learn More<div class="overflow-hidden ml-2"><span class="d-inline-block" data-zanim='{"from":{"opacity":0,"x":-30},"to":{"opacity":1,"x":0},"delay":0.8}'>&xrarr;</span></div></a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row no-gutters pos-relative mt-4 mt-lg-0">
                            <div class="elixir-caret d-none d-lg-block"></div>
                            <div class="col-lg-6 py-3 py-lg-0 mb-0 order-lg-2" style="min-height:400px;">
                                <div class="background-holder radius-tl-secondary radius-tl-lg-0 radius-tr-secondary radius-tr-lg-0" style="background-image:url(assets/images/7.jpg);"></div>
                                <!--/.background-holder-->
                            </div>
                            <div class="col-lg-6 px-lg-5 py-lg-6 p-4 my-lg-0 background-white radius-bl-secondary radius-bl-lg-0 radius-br-secondary radius-br-lg-0">
                                <div class="d-flex align-items-center h-100">
                                    <div data-zanim-timeline="{}" data-zanim-trigger="scroll">
                                        <div class="overflow-hidden">
                                            <h5 data-zanim='{"delay":0}'>Tax consulting</h5></div>
                                        <div class="overflow-hidden">
                                            <p class="mt-3" data-zanim='{"delay":0.1}'>Elixir serves clients across the country and around the world as they navigate an increasingly complex tax landscape. Our tax professionals draw on deep experience and industry-specific knowledge to deliver clients the insights and innovation they need.</p>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div data-zanim='{"delay":0.2}'><a class="d-flex align-items-center" href="#">Learn More<div class="overflow-hidden ml-2"><span class="d-inline-block" data-zanim='{"from":{"opacity":0,"x":-30},"to":{"opacity":1,"x":0},"delay":0.8}'>&xrarr;</span></div></a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row no-gutters pos-relative mt-4 mt-lg-0">
                            <div class="elixir-caret d-none d-lg-block"></div>
                            <div class="col-lg-6 py-3 py-lg-0 mb-0" style="min-height:400px;">
                                <div class="background-holder radius-tl-secondary radius-tr-secondary radius-tr-lg-0 radius-tl-lg-0 radius-bl-0 radius-bl-lg-secondary" style="background-image:url(assets/images/8.jpg);"></div>
                                <!--/.background-holder-->
                            </div>
                            <div class="col-lg-6 px-lg-5 py-lg-6 p-4 my-lg-0 background-white radius-bl-secondary radius-bl-lg-0 radius-br-secondary">
                                <div class="d-flex align-items-center h-100">
                                    <div data-zanim-timeline="{}" data-zanim-trigger="scroll">
                                        <div class="overflow-hidden">
                                            <h5 data-zanim='{"delay":0}'>Advisory</h5></div>
                                        <div class="overflow-hidden">
                                            <p class="mt-3" data-zanim='{"delay":0.1}'>To help you understand what this road looks like, we surveyed 1165 digital marketers across Europe and North America to explore current trends and priorities in digital marketing.</p>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div data-zanim='{"delay":0.2}'><a class="d-flex align-items-center" href="#">Learn More<div class="overflow-hidden ml-2"><span class="d-inline-block" data-zanim='{"from":{"opacity":0,"x":-30},"to":{"opacity":1,"x":0},"delay":0.8}'>&xrarr;</span></div></a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/.row-->
                    </div>
                    <!--/.container-->
                </section>
                @break
                @case(2)
                <section class="background-11  text-center">
                    <div class="container">
                        <div class="row mb-6">
                            <div class="col">
                                <h3 class="fs-2 fs-md-3">{{$content->menu}}</h3>
                                <hr class="short" data-zanim='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll">
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($content->submenus as $submenu)
                            <div class="col-sm-6 col-lg-4">
                                <div class="background-white pb-4 h-100 radius-secondary"><img class="mb-4 radius-tr-secondary radius-tl-secondary" src="assets/images/portrait-3.jpg" alt="Profile Picture" />
                                    <div class="px-4" data-zanim-timeline="{}" data-zanim-trigger="scroll">
                                        <div class="overflow-hidden">
                                            <h5 data-zanim='{"delay":0}'>{{$submenu->title}}</h5></div>
                                        <div class="overflow-hidden">
                                            <h6 class="fw-400 color-7" data-zanim='{"delay":0.1}'>Advertising Consultant</h6></div>
                                        <div class="overflow-hidden">
                                            <p class="py-3 mb-0" data-zanim='{"delay":0.2}'>{{$submenu->description}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                        <!--/.row-->
                    </div>
                    <!--/.container-->
                </section>
                @break
                @default
                <section class="background-11">
                    <div class="container">
                        <h3 class="text-center fs-2 fs-md-3">{{$content->menu}}</h3>
                        <div class="owl-carousel owl-theme owl-nav-outer owl-dot-round mt-8" data-options='{"items":3}'>
                            <div class="item mx-2"><img class="radius-secondary" src="assets/images/portrait-3.jpg"></div>
                            <div class="item mx-2"><img class="radius-secondary" src="assets/images/portrait-4.jpg"></div>
                            <div class="item mx-2"><img class="radius-secondary" src="assets/images/portrait-5.jpg"></div>
                            <div class="item mx-2"><img class="radius-secondary" src="assets/images/portrait-6.jpg"></div>
                            <div class="item mx-2"><img class="radius-secondary" src="assets/images/portrait-7.jpg"></div>
                            <div class="item mx-2"><img class="radius-secondary" src="assets/images/portrait-1.jpg"></div>
                        </div>
                    </div>
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
                            <div class="row px-lg-8">
                                <div class="col-4 col-md-3 mx-auto"><img class="radius-secondary mx-auto" src="assets/images/client1.png" alt="Member" style="width: auto" /></div>
                                <div class="col-md-9 ml-auto mt-4 mt-md-0 px-4 px-sm-3">
                                    <p class="lead fw-400">Their work on our website and Internet marketing has made a significant different to our business. We’ve seen a 425% increase in quote requests from the website which has been pretty remarkable – but I’d always like to see more!</p>
                                    <h6 class="fs-0 mb-1 mt-4">Michael Clarke</h6>
                                    <p class="mb-0 color-7">CEO, A.E.T Institute</p>
                                </div>
                            </div>
                            <div class="row px-lg-8">
                                <div class="col-4 col-md-3 mx-auto"><img class="radius-secondary mx-auto" src="assets/images/client2.png" alt="Member" style="width: auto" /></div>
                                <div class="col-md-9 ml-auto mt-4 mt-md-0 px-4 px-sm-3">
                                    <p class="lead fw-400">Writing case studies was a daunting task for us. We didn’t know where to begin or what questions to ask, and clients never seemed to follow through when we asked. Elixir team did everything – with almost no time or effort for me!</p>
                                    <h6 class="fs-0 mb-1 mt-4">Maria Sharapova</h6>
                                    <p class="mb-0 color-7">Managing Director, Themewagon Inc.</p>
                                </div>
                            </div>
                            <div class="row px-lg-8">
                                <div class="col-4 col-md-3 mx-auto"><img class="radius-secondary mx-auto" src="assets/images/client3.png" alt="Member" style="width: auto" /></div>
                                <div class="col-md-9 ml-auto mt-4 mt-md-0 px-4 px-sm-3">
                                    <p class="lead fw-400">As a sales gamification company, we were skeptical to work with a consultant to optimize our sales emails, but Elixir was highly recommended by many other Y-Combinator startups we knew. Elixir helped us run a ~6 week email campaign.</p>
                                    <h6 class="fs-0 mb-1 mt-4">David Beckham</h6>
                                    <p class="mb-0 color-7">Chairman, Harmony Corporation</p>
                                </div>
                            </div>
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
                    <div class="col-4 col-md-2 my-3 overflow-hidden"><img src="assets/images/partner/logo2.png" alt="" data-zanim="{}" /></div>
                    <div class="col-4 col-md-2 my-3 overflow-hidden"><img src="assets/images/partner/logo1.png" alt="" data-zanim="{}" /></div>
                    <div class="col-4 col-md-2 my-3 overflow-hidden"><img src="assets/images/partner/logo6.png" alt="" data-zanim="{}" /></div>
                    <div class="col-4 col-md-2 my-3 overflow-hidden"><img src="assets/images/partner/logo3.png" alt="" data-zanim="{}" /></div>
                    <div class="col-4 col-md-2 my-3 overflow-hidden"><img src="assets/images/partner/logo5.png" alt="" data-zanim="{}" /></div>
                    <div class="col-4 col-md-2 my-3 overflow-hidden"><img src="assets/images/partner/logo4.png" alt="" data-zanim="{}" /></div>
                </div>
                <!--/.row-->
            </div>
            <!--/.container-->
        </section>
@endsection