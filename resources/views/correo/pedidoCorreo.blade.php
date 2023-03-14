<!doctype html>
<html lang="es-mx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Pedido</title>
</head>
<body>
    <h1>CORREO ENVIADO POR SISTEMA WEB DIVINA JOYA</h1>
    <h3>ingrese al sistemas mediante el siguiente link: <a href="www.divinajoya.com">www.divinajoya.com</a></h3>
    <table>
        <tbody>
            <tr><th style="color:blue;text-align:right;">PEDIDO:</th><td style="color:red;">{{$pedidoSeguimiento->pedido_id}}</td></tr>
            <tr><th style="color:blue;text-align:right;">ETAPA:</th><td>{{$pedidoSeguimiento->etapa->descripcion}}</td></tr>
            <tr><th style="color:blue;text-align:right;">Fecha Hora de Envio:</th><td>{{Carbon\Carbon::parse(Carbon\Carbon::now('America/La_Paz')->toDateTimeString())->format('d/m/Y H:i:s')}}</td></tr>
            <tr><th style="color:blue;text-align:right;">MENSAJE:</th><td>{{$pedidoSeguimiento->observacion}}</td></tr>
        </tbody>
    </table>
</body>
</html>