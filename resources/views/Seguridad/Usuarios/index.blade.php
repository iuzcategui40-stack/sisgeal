@extends('layouts.app')
@section('titulo','Seguridad - Usuarios')
@section('link_back_none',"d-none")
@section('link_new',route('usuarios.create'))

@section('content')
@if(Session::has('mensaje'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Exito!</strong> {!!Session::get('mensaje')!!}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
@if(Session::has('mensajeError'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Exito!</strong> {!!Session::get('mensajeError')!!}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif

<!-- BOTÓN NUEVO USUARIO AGREGADO EN LA ESQUINA SUPERIOR IZQUIERDA -->

          
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">     
                <div class="table-responsive">
                    <table class="table data-table" id="listausuarios">
                        <thead>
                            <tr>
                                <th>Nombres</th>
                                <th>Usuario</th>
                                <th>Activo</th>
                                <th>Rol</th>
                                <th>....</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->nombres }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>
                                    @if($usuario->id_estado==1)
                                        <div class="col">
                                            <p class="mb-2">Si</p>
                                        </div>
                                    @else
                                        <div class="col">
                                            <p class="mb-2">No</p>                     
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $usuario->role }}</td>
                                <td class="td-actions">
                                    <a href="{{ route('usuarios.edit',$usuario->id) }}" title="Editar Registro">
                                        <i class="mdi mdi-table-edit"></i>
                                    </a>

                                    <?php 
                                    $valor = $usuario->id; 
                                    $url="admin/usuarios/".$valor."/cambiar"; ?>
                                    <a data-toggle="tooltip" data-placement="top" href="{{ route("usuarios.cambiar",$valor) }}" title="Cambiar Contraseña"><i class="mdi mdi-account-key"></i></a>

                                    @switch($usuario->id_estado)
                                        @case(1)
                                        <a title="Desactivar" href="{{ route('usuarios.estatus',$usuario->id)}}">
                                            <i class="mdi mdi-account-off"></i>
                                        </a>
                                        @break
                                        @case(0)
                                        <a title="Activar" href="{{ route('usuarios.estatus',$usuario->id)}}">
                                            <i class="mdi mdi-account-convert"></i>
                                        </a>
                                        @break
                                        @case(2)
                                        <a title="Activar" href="{{ route('usuarios.estatus',$usuario->id)}}">
                                            <i class=" mdi mdi-account-remove"></i>
                                        </a>
                                        @break
                                        @default                          
                                    @endswitch
                                      
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection

@push("scripts")
<script type="text/javascript">
  $(function () {
    var table = $('.data-table').DataTable({
      "language": {
        "url": "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
      },      
    });
  });  

  $(document).ready(function() {
    $('#listausuarios').DataTable();

    $('#status').on('click', function(e){
      id = $(this).data('id');
      console.log(id);
      estaSeleccionado = $('input:checkbox[name=status]:checked').val();
      if(estaSeleccionado == 'on') activo = 1;
      else activo = 0;
    
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      url="usuarios/estatus";
      $.ajax({
        type: "post",
        url: url,
        dataType: "json",
        cache: false,
        data: {id: id},
        success: function (data){
          console.log(data);
          location.reload();          
        }
      });
    });  
  });
</script>
@endpush