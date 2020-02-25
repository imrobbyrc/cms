<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="#">CARBON</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="#">C</a>
    </div>
    <ul class="sidebar-menu">   
      <li class="menu-header">Dashboard</li>
      <li class="@if(request()->segment(2)=='home') active @endif"><a class="nav-link" href="/admin/home"><i class="fab fa-dashcube"></i> <span>Dashboard</span></a></li>
      
      <li class="dropdown @if(request()->segment(2)=='homepage') active @endif">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-home"></i> <span>Homepage</span></a>
        <ul class="dropdown-menu">
          <li class="@if(request()->segment(3)=='main-slider') active @endif"><a href="/admin/homepage/main-slider"><i class="fas fa-images"></i>Main Slider</a></li>
          <li class="@if(request()->segment(3)=='header-content') active @endif"><a href="/admin/homepage/header-content"><i class="fas fa-bookmark"></i>Header Content</a></li>
          <li class="@if(request()->segment(3)=='footer-content') active @endif"><a href="/admin/homepage/footer-content"><i class="fas fa-shoe-prints"></i>Footer Content</a></li>
        </ul>
      </li>
            
      <li class="dropdow @if(request()->segment(2)=='contact-us') active @endif">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-phone"></i><span>Contanct Us</span></a>
        <ul class="dropdown-menu">
          <li class="@if(request()->segment(3)=='contact-us') active @endif"><a href="/admin/contact-us/contact-us"><i class="fas fa-images"></i>Contact</a></li>
          <li class="@if(request()->segment(3)=='location') active @endif"><a href="/admin/contact-us/location"><i class="fas fa-map-marker-alt"></i>Location</a></li> 
        </ul>
      </li>
       
       <li class="dropdown @if(request()->segment(2)=='content') active @endif">
         <a href="#" class="nav-link has-dropdown"><i class="far fa-newspaper"></i> <span>Content Pages</span></a>
         <ul class="dropdown-menu">
           <li class="@if(request()->segment(3)=='menu') active @endif"><a href="/admin/content/menu"><i class="fas fa-bars"></i>Menu</a></li>
           <li class="@if(request()->segment(3)=='submenu') active @endif"><a href="/admin/content/submenu"><i class="far fa-minus-square"></i>Submenu</a></li>
           <li class="@if(request()->segment(3)=='content') active @endif"><a href="/admin/content/content"><i class="far fa-newspaper"></i></i>Content</a></li>
         </ul>
       </li>

      <li class="@if(request()->segment(2)=='partnership') active @endif">
        <a class="nav-link" href="/admin/partnership"><i class="far fa-handshake"></i><span>Partnership</span></a>
      </li>

      <li class="@if(request()->segment(2)=='testimonial') active @endif">
        <a class="nav-link" href="/admin/testimonial"><i class="far fa-comments"></i><span>Testimonial</span></a>
      </li>

      <li class="@if(request()->segment(2)=='inbox') active @endif">
        <a class="nav-link" href="/admin/inbox"><i class="far fa-envelope"></i><span>Inbox</span></a>
      </li> 

      {{-- @role('admin') --}}
      <li class="menu-header ">Administrator</li>
      <li class="dropdown @if(request()->segment(2)=='users' || request()->segment(2)=='role') active @endif">
        <a href="#" class="nav-link has-dropdown"><i class="far fa-user"></i> <span>Auth</span></a>
        <ul class="dropdown-menu">
          <li class="@if(request()->segment(2)=='users' && request()->segment(3)=='' ) active @endif"><a href="{{ route('users.index') }}">Users</a></li>
          <li class="@if(request()->segment(2)=='role') active @endif"><a href="{{route('role.index')}}">Roles</a></li>
          <li class="@if(request()->segment(3)=='role-permission') active @endif"><a href="{{route('users.roles_permission')}}">Role Permission</a></li>
        </ul>
      </li>
      {{-- @endrole --}}
              
    </ul>
  </aside>
</div>

 

          
        
        





          
  