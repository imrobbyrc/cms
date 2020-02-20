
        <section class="background-primary py-3 d-none d-sm-block">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-6 text-left color-white">
                        {!!$topmenu->contentLeft!!}
                    </div>
                    <div class="col-auto ml-md-auto order-md-2 d-none d-sm-block">
                    </div>
                    <div class="col-6 text-right color-white">
                        {!!$topmenu->contentRight!!}
                    </div>
                </div>
                <!--/.row-->
            </div>
            <!--/.container-->
        </section>
        <div class="znav-white znav-container sticky-top navbar-elixir" id="znav-container">
            <div class="container">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand overflow-hidden pr-3" href="/">
                    <img src="{{$topmenu->headerLogo}}" alt="" /></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <div class="hamburger hamburger--emphatic">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                        <ul class="navbar-nav fs-0 fw-700">
                            <li><a class="d-block" href="/">Home</a></li>
                            @foreach ($menus as $menu)
                                <li><a href="/{{$menu->link}}">{{$menu->menu}}</a>
                                    @if (!empty($menu->submenus))
                                    <ul class="dropdown fs--1">
                                        @foreach ($menu->submenus as $submenu)
                                                <li><a href="/{{$menu->link}}/{{$submenu->link}}">{{$submenu->submenus}}</a></li> 
                                           
                                        @endforeach 
                                    </ul>        
                                    @endif
                                </li>
                            @endforeach
                            <li><a class="d-block" href="contact-us">Contact Us</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>