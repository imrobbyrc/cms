@include('partials.header')
@include('partials.stylesheet')   
  <body data-spy="scroll" data-target=".inner-link" data-offset="60"> 
    <main>
    @include('partials.navbar') 
      @yield('content') 
    </main>
  </body> 

@include('partials.footer') 
@include('partials.script') 