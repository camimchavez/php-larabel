<aside class="main-sidebar sidebar-dark-primary elevation-4" style="overflow-x:hidden">
    
    <!-- Brand Logo -->
    <a href="{{url('/')}}" class="brand-link">
      <img src="{{url('/')}}/{{$blog[0]["icono"]}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Blog del Viajero</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition"><div class="os-resize-observer-host observed"><div class="os-resize-observer" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer"></div></div><div class="os-content-glue" style="margin: 0px -8px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;"><div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          @foreach ($administradores as $element) 
            @if ($_COOKIE["email_login"] == $element->email)
              @if($element->foto == "")
                <img src="{{url('/')}}/vistas/img/admin.png" class="img-circle elevation-2" alt="User Image">

              @else

                <img src="{{url('/')}}/{{$element->foto}}" class="img-circle elevation-2" alt="User Image">
        
              @endif
        
            @endif
          @endforeach
          
        </div>
        <div class="info">
          <a href="#" class="d-block">
            @foreach ($administradores as $element) 
              @if ($_COOKIE["email_login"] == $element->email)
                {{ $element->name }}
              @endif
            @endforeach
          </a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      {{-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div><div class="sidebar-search-results"><div class="list-group"><a href="#" class="list-group-item"><div class="search-title"><strong class="text-light"></strong>N<strong class="text-light"></strong>o<strong class="text-light"></strong> <strong class="text-light"></strong>e<strong class="text-light"></strong>l<strong class="text-light"></strong>e<strong class="text-light"></strong>m<strong class="text-light"></strong>e<strong class="text-light"></strong>n<strong class="text-light"></strong>t<strong class="text-light"></strong> <strong class="text-light"></strong>f<strong class="text-light"></strong>o<strong class="text-light"></strong>u<strong class="text-light"></strong>n<strong class="text-light"></strong>d<strong class="text-light"></strong>!<strong class="text-light"></strong></div><div class="search-path"></div></a></div></div>
      </div> --}}

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            @foreach ($administradores as $element) 
              @if ($_COOKIE["email_login"] == $element->email)
                @if ($element->rol == "administrador")
                  <!--=====================================
                  Bot??n Blog
                  ======================================-->

                  <li class="nav-item">
                    <a href="{{ url("/") }}" class="nav-link">
                      <i class="nav-icon fas fa-home"></i>
                      <p>Blog</p>
                    </a>
                  </li>

                  <!--=====================================
                  Bot??n Administradores
                  ======================================-->

                  <li class="nav-item">
                    <a href="{{ url("/administradores") }}" class="nav-link">
                      <i class="nav-icon fas fa-users-cog"></i>
                      <p>Administradores</p>
                    </a>
                  </li>

                            
                @endif
              @endif
            @endforeach

            
          
          <!--=====================================
          Bot??n Categor??as
          ======================================-->

          <li class="nav-item">
            <a href="{{ url("/categorias") }}" class="nav-link">
              <i class="nav-icon fas fa-list-ul"></i>
              <p>Categor??as</p>
            </a>
          </li>

          <!--=====================================
          Bot??n Art??culos
          ======================================-->

          <li class="nav-item">
            <a href="{{ url("/articulos") }}" class="nav-link">
              <i class="nav-icon fas fa-sticky-note"></i>
              <p>Art??culos</p>
            </a>
          </li>

          <!--=====================================
          Bot??n Opiniones
          ======================================-->

          <li class="nav-item">
            <a href="{{ url("/opiniones") }}" class="nav-link">
              <i class="nav-icon fas fa-user-check"></i>
              <p>Opiniones</p>
            </a>
          </li>

          <!--=====================================
          Bot??n Banner
          ======================================-->

          <li class="nav-item">
            <a href="{{ url("/banner") }}" class="nav-link">
              <i class="nav-icon far fa-images"></i>
              <p>Banner</p>
            </a>
          </li>

          <!--=====================================
          Bot??n Anuncios
          ======================================-->

          <li class="nav-item">
            <a href="{{ url("/anuncios") }}" class="nav-link">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>Anuncios</p>
            </a>
          </li>

          <!--=====================================
          BOT??N SITIO WEB
          ======================================-->

          <li class="nav-item">
          
          <a href="{{ substr(url("/"),0,-11) }}" class="nav-link" target="_blank">
            
            <i class="nav-icon fas fa-globe"></i>
            
            <p>Ver sitio</p>

          </a>

        </li>
 



        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div></div></div><div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="height: 35.5222%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar-corner"></div></div>
    <!-- /.sidebar -->
  </aside>