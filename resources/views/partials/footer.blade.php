
        <section style="background-color: #3D4C6F">
            <div class="container">
                <div class="row align-items-center"> 
                    @foreach ($botmenu as $item) 
                    <div class="col-6 col-lg-3 color-white ml-lg-auto">
                        {!! $item->description !!}
                    </div>
                    @endforeach
                </div>
                <!--/.row-->
            </div>
            <!--/.container-->
        </section>
        <section class="background-primary text-center py-4">
            <div class="container">
                <div class="row align-items-center" style="opacity: 0.85">
                    <div class="col-sm-3 text-sm-left">
                        <a href="index-2.html"><img src="{{$topmenu->headerLogo}}" alt="" /></a>
                    </div>
                    <div class="col-sm-6 mt-3 mt-sm-0">
                        <p class="color-white lh-6 mb-0 fw-600">&copy; Copyright 2020 innizhcarbon.com</p>
                    </div>
                    <div class="col text-sm-right mt-3 mt-sm-0"></div>
                </div>
                <!--/.row-->
            </div>
            <!--/.container-->
        </section> 
</html>