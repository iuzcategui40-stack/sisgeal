@extends('layouts.app')

@section('titulo', 'Bienvenidos al Sistema de Gestión de Archivo legislativo')
@section('link_back_none',"d-none")
@section('link_new_none',"d-none")
@section('content')
          @include("layouts.grafico1")
          @include("layouts.grafico2")
          @include("layouts.grafico3")
          @include("layouts.tabla")
          @include("layouts.tabla2")
         
@endsection
