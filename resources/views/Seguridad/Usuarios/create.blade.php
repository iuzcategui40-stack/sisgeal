@extends('layouts.app')
@section('titulo', 'Seguridad - Usuarios')
@section('link_back', route('usuarios.index'))
@section('link_new_none', 'd-none')

@section('content')   

    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="badge badge-pill badge-danger">AVISO:</span> 
            @foreach ($errors->all() as $error)
                {{ $error }} <br>
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Crear Usuario</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Atrás</a></li>
        </ol>
    </div>          

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Registro de Usuario</h4>
                    <p class="card-description">Complete los Datos</p>
                
                    <form id="form-crear-usuario" action="{{ route('usuarios.store') }}" method="POST" enctype="multipart/form-data" class="forms-sample">
                        @csrf
                        
                        {{-- 1. Nombre --}}
                        <div class="form-group row">
                            <div class="col-sm-3 col-form-label"> 
                                <label for="name"><b>Apellidos y Nombres</b></label>
                            </div>
                            <div class="col-sm-9"> 
                                <input placeholder="Nombre del Usuario" id="name" type="text" class="form-control" name="nombre" value="{{ old('nombre') }}" required autocomplete="name" autofocus oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');">
                                <small class="help-block form-text text-muted">Por favor ingresa apellidos y nombres del usuario</small>
                            </div>
                        </div>


{{-- 2. Login / Email --}}
<div class="form-group row">
    <div class="col-sm-3 col-form-label"> 
        <label for="email"><b>Login de Usuario</b></label>
    </div>
    <div class="col-sm-9">
        <div class="input-group">
            <input placeholder="Ejemplo: pperez@email.com" id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email">
            <div class="input-group-append d-none" id="email-spinner">
                <span class="input-group-text bg-transparent border-0">
                    <i class="ti-reload spinner-border-sm text-primary"></i>
                </span>
            </div>
        </div>
        
        {{-- Mensaje de error dinámico --}}
        <div id="email-error-msg" class="invalid-feedback d-none"></div>
        {{-- Mensaje de éxito dinámico --}}
        <div id="email-success-msg" class="valid-feedback d-none">El correo está disponible.</div>
        <div id="email-error-msg" class="invalid-feedback d-none">
                                    Login incorrecto. Ingrese una dirección de correo electrónico válida (Ej: usuario@dominio.com).
                                </div>
        <small class="help-block form-text text-muted">Ingresa login del usuario Ej: jcarrillo@email.com</small>
    </div>
</div>



                       
                        {{-- 3. Perfil --}}
                        <div class="form-group row">
                            <div class="col-sm-3 col-form-label"> 
                                <label for="role_id"><b>Perfil de Usuario</b></label>
                            </div>
                            <div class="col-sm-9">
                                <select id="role_id" class="form-control" name="role_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->description }}</option>
                                    @endforeach                        
                                </select>        
                            </div>
                        </div>

                        {{-- 4. Contraseña --}}
                        <div class="form-group row">
                            <div class="col-sm-3 col-form-label"> 
                                <label for="password"><b>Password/Contraseña</b></label>
                            </div>
                            <div class="col-sm-9">
                                <input placeholder="Clave" id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
                            </div> 
                        </div>

                        {{-- 5. Confirmación Contraseña --}}
                        <div class="form-group row">
                            <div class="col-sm-3 col-form-label"> 
                                <label for="password-confirm"><b>Confirmar Contraseña</b></label>
                            </div>
                            <div class="col-sm-9">   
                                <input placeholder="Confirmación Clave" id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">               
                                <small class="help-block form-text text-muted">Por favor repita la contraseña</small>
                            </div>    
                        </div> 

                        <div class="card-footer bg-transparent border-0 px-0 mt-3">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-dot-circle-o"></i> {{ __('Registrar') }}
                            </button>
                            <button type="reset" class="btn btn-danger btn-sm">
                                <i class="fa fa-ban"></i> Borrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

{{-- SCRIPT DE VALIDACIÓN EN TIEMPO REAL --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('email');
    const errorMsg = document.getElementById('email-error-msg');
    const successMsg = document.getElementById('email-success-msg');
    const form = document.getElementById('form-crear-usuario');
    
    let timer = null;
    let isEmailAvailable = false;

    // Expresión regular para validar formato de correo electrónico
    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    function validarYComprobar() {
        const valor = emailInput.value.trim();

        // 1. Validar formato básico
        if (valor === '' || !regexEmail.test(valor)) {
            marcarInvalido('Login incorrecto. Ingrese una dirección de correo válida (Ej: usuario@dominio.com).');
            isEmailAvailable = false;
            return;
        }

        // 2. Si el formato es correcto, hacer consulta AJAX al servidor
        fetch("{{ route('usuarios.checkEmail') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: valor })
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                marcarInvalido(data.message);
                isEmailAvailable = false;
            } else {
                marcarValido();
                isEmailAvailable = true;
            }
        })
        .catch(error => {
            console.error('Error al verificar el correo:', error);
        });
    }

    function marcarInvalido(mensaje) {
        emailInput.classList.remove('is-valid');
        emailInput.classList.add('is-invalid');
        errorMsg.textContent = mensaje;
        errorMsg.classList.remove('d-none');
        errorMsg.style.display = 'block';
        
        successMsg.classList.add('d-none');
        successMsg.style.display = 'none';
    }

    function marcarValido() {
        emailInput.classList.remove('is-invalid');
        emailInput.classList.add('is-valid');
        
        errorMsg.classList.add('d-none');
        errorMsg.style.display = 'none';

        successMsg.classList.remove('d-none');
        successMsg.style.display = 'block';
    }

    // Evento mientras el usuario escribe (con un debounce de 400ms para evitar peticiones excesivas)
    emailInput.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(validarYComprobar, 400);
    });

    // Evento al presionar ENTER
    emailInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(timer);
            validarYComprobar();
        }
    });

    // Evento al salir del campo (blur)
    emailInput.addEventListener('blur', function () {
        if (emailInput.value.trim() !== '') {
            clearTimeout(timer);
            validarYComprobar();
        }
    });

    // Bloquear envío del formulario si el correo no es válido o ya existe
    form.addEventListener('submit', function (e) {
        if (!isEmailAvailable) {
            e.preventDefault();
            validarYComprobar();
            emailInput.focus();
        }
    });
});
</script>
@endsection