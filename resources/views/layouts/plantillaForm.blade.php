@extends('layouts.plantilla')
@section('content')
    @yield('contenido')
    <div class="  ">
        <div class="card-header paddingminimo ">
            @yield('panelCabecera')
        </div>
        <div class="card-body paddingminimo ">
            @yield('panelCuerpo')
        </div>

        <!-- @yield('panelTabla') -->
        <!-- <div class="card-footer paddingminimo">
            @yield('panelPie')
        </div> -->
    </div>
@endsection
@push('scripts')
  <script>
     $("#main_content").show();
  </script>
@endpush
