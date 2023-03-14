@extends('layouts.plantillaForm')
@section('contenido')
    @section('panelCabecera')
        <div class="row">
            <div class="col-md-4 col-xm-12 offset-md-5">
                CAMBIO DE CONTRASEÑA
            </div>
        </div>
    @endsection

    @section('panelCuerpo')

        <form id="frm_cambiar_pass" method="POST" action="{{url('/seguridad/usuarios/updatepass')}}" autocomplete="off">
            @csrf
                <input type="hidden" name="usuario_id" value="{{Auth::user()->id}}">
                <div class="col">
                    <div class="form-group">
                        <label for="password"  class="my-0"><strong>Nuevo Passwrod:</strong></label>
                        <input id="password" type="password" class="form-control" name="password" required>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="password_confirmation"  class="my-0"><strong>Confime Nuevo Password:</strong></label>
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
                <div id="msg-info" class="col text-danger">

                </div>

            <div class=" d-flex justify-content-around">
                        <button type="submit" class="btn btn-success">Cambiar</button>
                        <a class="btn btn-primary" href="{{route('home')}}">Cancelar</a>
            </div>
        </form>
    @endsection
@endsection

@push('scripts')
    <script>
        $('#frm_cambiar_pass').on('submit', function (event) {
            var form = $('#frm_cambiar_pass');
            var password = $('#password').val();
            var password_confirmation = $('#password_confirmation').val();
            $("#msg-info").html("");
            if(password!=password_confirmation ) {
                event.preventDefault();//evitando realizar submit
                // event.stopPropagation();
                $("#msg-info").html("el password no coincide");
            }
            //$(this).unbind('submit').submit();//continua con submit
        });
    </script>
@endpush
