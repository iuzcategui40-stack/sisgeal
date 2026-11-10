@php
use App\Models\User;

$dia = date("d");
$an = date("Y");
$nmes = date("M");
$user = User::where('id',$id_usuario)->first();
$name = $user->nombres;
@endphp

<div class="col-md-12 grid-margin">
    <div class="row">
         <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h4 class="font-weight-bold">@yield("titulo") - Usuario: {{ $name}}</h4>
                  <div class="template-demo d-flex flex-nowrap">
                     <a href="{{route('home')}}" class="btn btn-primary"><i class="ti-home" title="Inicio"></i></a>
                      <a class="btn btn-success @yield('link_new_none')" href="@yield('link_new')" @yield('enlace_none') title="Nuevo"><i class="mdi mdi-database-plus"></i></a>
                     <a class="btn btn-secondary @yield('link_back_none')" href="@yield('link_back')" @yield('enlace_none')><i class="mdi mdi-arrow-left-bold"></i></a>

                  </div>
                </div>
        <div class="col-12 col-xl-4">
            <div class="justify-content-end d-flex">
                <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                    <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="mdi mdi-calendar"></i> Hoy es ({{ $dia }} de {{ $nmes}} de {{ $an }})
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>