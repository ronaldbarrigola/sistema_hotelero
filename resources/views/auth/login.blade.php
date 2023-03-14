<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <!-- Bootstrap 4.5 -->
    <link rel="stylesheet" href="{{asset('css/bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bspersonalizado.css')}}">

</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header"><strong>Login</strong></div>
                <div class="card-body">
                    <form  method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="login" class="col-md-4 col-form-label"><strong>Usuario:</strong></label>
                            <div class="col-md-6">
                                <input id="login" type="text" class="form-control" name="login"
                                value="{{ $usuario!=null?$usuario:'' }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label"><strong>Contraseña:</strong></label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        @if($mensaje!=null)
                            <p class="alert alert-danger">{{$mensaje}}</p>
                        @endif

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Ingresar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
     <!-- Scripts -->
      <!-- jQuery 2.1.4 -->
      <script src="{{asset('js/jquery/jquery-3.5.1.min.js')}}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('js/bootstrap/bootstrap.min.js')}}"></script>
</body>
</html>
