@extends('layouts.plantillaFormExtendido')
@section('contenido')
    @section('panelCabecera')
        @include('business/transaccion/actionbar',['','titulo'=>'TRANSACCION'])
    @endsection

    @section('panelCuerpo')
       @include('business/transaccion/datatable_transaccion')
    @endsection
@endsection
