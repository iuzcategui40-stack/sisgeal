<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{ route("home") }}">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Inicio</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-layout menu-icon"></i>
              <span class="menu-title">Parametrización</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">

           
                  <li class="nav-item"> <a class="nav-link" href="{{ route('tiposactos.index') }}">Tipo Acto</a></li>
          
                {{--<li class="nav-item"> <a class="nav-link" href="{{ route('tiposactos.index') }}">Tipo Acto</a></li>  --}}
                
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
              <i class="icon-columns menu-icon"></i>
              <span class="menu-title">Registro</span>
              <i class="menu-arrow"></i>
            </a>
            
            <div class="collapse" id="form-elements">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="{{route('actoslegislativos.index')}}">Actos Legislativos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('sesiones.index') }}">Diario de Debates</a></li>
              </ul>
            </div>

          </li>
         
 
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
              <i class="icon-head menu-icon"></i>
              <span class="menu-title">Seguridad</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
              <ul class="nav flex-column sub-menu">
                 
                    <li class="nav-item"> 
                        <a class="nav-link" href="{{ route('roles.index') }}"> Roles y Permisos </a>
                    </li>
            
                <li class="nav-item"> <a class="nav-link" href="{{route('usuarios.index')}}"> Usuarios </a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('seguridad.audits.index')}}"> Auditoria </a></li>
                
              </ul>
            </div>
          </li>
          <li class="nav-item">
    <a class="nav-link" href="{{ route('documentacion.index') }}">
        <i class="icon-book menu-icon"></i>
        <span class="menu-title">Documentación</span>
    </a>
</li>
       
       
        </ul>
      </nav>