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
                                    <h5 data-zanim='{"delay":0}'>Message From CEO</h5>
                                    <p class="my-4" data-zanim='{"delay":0.1}'>Elixir co-operates with clients in solving the hardest problems they face in their businesses—and the world. We do this by channeling the diversity of our people and their thinking. </p><img data-zanim='{"delay":0.2}' src="assets/images/signature.png" alt="" />
                                    <h5 class="text-uppercase mt-3 fw-500 mb-1" data-zanim='{"delay":0.3}'>renal scott</h5>
                                    <h6 class="color-7 fw-600" data-zanim='{"delay":0.4}'>UK office</h6></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-6">
                        <div class="col">
                            <h3 class="text-center fs-2 fs-md-3">Company Overview</h3>
                            <hr class="short" data-zanim='{"from":{"opacity":0,"width":0},"to":{"opacity":1,"width":"4.20873rem"},"duration":0.8}' data-zanim-trigger="scroll" />
                        </div>
                        <div class="col-12">
                            <div class="background-white px-3 mt-6 px-0 py-5 px-lg-5 radius-secondary">
                                <h5>Earning the right</h5>
                                <p class="mt-3">As a first order business consulting firm, we help companies, foundations and individuals make a difference. Our work gets to the heart of the matter. We break silos because it takes more than any one check or policy or letter to tackle big issues like economic security, human rights or climate sustainability. We prescribe a custom formula of advocacy, collaboration, investment, philanthropy, policy and new ways of doing business in order to help you make progress.</p>
                                <blockquote class="blockquote my-5 ml-lg-6" style="max-width: 700px;">
                                    <h5 class="fw-500 ml-3 mb-0">But how do we do it? We like to call it earning the right, walking the talk and playing the game &hellip;</h5></blockquote>
                                <p class="column-lg-2 dropcap">Elixir serves to help people with creative ideas succeed. Our platform empowers millions of people — from individuals and local artists to entrepreneurs shaping the world’s most iconic businesses — to share their stories and create an impactful, stylish, and easy-to-manage online presence. The Cambridge office is the home of the Risk management practice. We work to assure the safe performance of complex critical systems; develop safety leadership and culture; manage safety and risk in high-hazard industries; understand complex project risks, measure and report risk performance. We work across a wide range of industries and public sector organizations that include upstream and downstream oil and gas; rail and road transportation; construction; and gas utilities and distribution. We work worldwide in Europe, Middle East and Asia, Africa and South America based out of our offices in Cambridge, UK and Milan, Italy.</p>
                            </div>
                        </div>
                    </div>
                    <!--/.row-->
                </div>
                <!--/.container-->
            </section>
        </div>
@endsection