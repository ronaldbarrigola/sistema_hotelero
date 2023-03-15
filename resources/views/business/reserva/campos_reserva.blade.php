
<div class="row">

    <input type="hidden" name="reserva_id" id="reserva_id">

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="cliente_id" class="my-0" ><strong>Reservado por:</strong></label>
            <select name="cliente_id" id="cliente_id"  class="form-control selectpicker border" data-live-search="true" >
               <option value="">--Seleccione--</option>
               @foreach($clienes as $lista_clientes)
                 <option value="{{$lista_clientes->id}}"> {{$lista_clientes->cliente}}</option>
               @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="habitacion_id" class="my-0" ><strong>Habitacion:</strong></label>
            <select name="habitacion_id" id="habitacion_id"  class="form-control selectpicker border" data-live-search="true" >
               <option value="">--Seleccione--</option>
               @foreach($habitaciones as $lista_habitacion)
                 <option value="{{$lista_habitacion->id}}" data-tipohabitacion="{{$lista_habitacion->tipo_habitacion}}">{{$lista_habitacion->habitacion}}</option>
               @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
           <label for="tipo_habitacion" class="my-0" ><strong>Tipo Habitacion:</strong></label>
           <input type="text" name="tipo_habitacion" id="tipo_habitacion" readonly class="form-control">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="paquete_id" class="my-0" ><strong>Paquete:</strong></label>
            <select name="paquete_id" id="paquete_id"  class="form-control selectpicker border" data-live-search="true" >
               <option value="">--Seleccione--</option>
               @foreach($paquetes as $lista_paquete)
                 <option value="{{$lista_paquete->id}}"> {{$lista_paquete->descripcion}}</option>
               @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="pais_id" class="my-0" ><strong>Pais:</strong></label>
            <select name="pais_id" id="pais_id"  class="form-control selectpicker border" data-live-search="true" >
               <option value="">--Seleccione--</option>
               @foreach($paises as $lista_pais)
                 <option value="{{$lista_pais->id}}"> {{$lista_pais->descripcion}}</option>
               @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
           <label for="ciudad_id" class="my-0" ><strong>Ciudad:</strong></label>
           <select name="ciudad_id" id="ciudad_id"  class="form-control selectpicker border" data-live-search="true" >

           </select>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group">
            <label for="detalle" class="my-0"><strong>Detalle:</strong></label>
            <input type="text" name="detalle" id="detalle" class="form-control">
        </div>
    </div>

</div>
