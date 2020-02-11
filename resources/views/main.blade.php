@include('partials.header')
@include('partials.stylesheet')  
  
  <body>      
      @include('partials.navbar')  
      @yield('content') 
  </body> 

@include('partials.footer') 
@include('partials.script') 