
<div class="row">

    <input type="hidden" name="habitacion_id" id="habitacion_id">

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="codigo" class="my-0"><strong>Numero Habitacion:</strong></label>
            <input type="text" name="codigo" id="codigo" class="form-control">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="descripcion" class="my-0"><strong>Descripcion Habitacion:</strong></label>
            <input type="text" name="descripcion" id="descripcion" class="form-control">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
           <label for="piso" class="my-0"><strong>Piso:</strong></label>
           <input type="text" name="piso" id="piso" class="form-control">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="precio" class="my-0"><strong>Precio:</strong></label>
            <input type="number" name="precio" id="precio" class="form-control">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="tipo_habitacion_id" class="my-0" ><strong>Tipo Habitacion:</strong></label>
            <select name="tipo_habitacion_id" id="tipo_habitacion_id"  class="form-control selectpicker border" data-live-search="true" >
               <option value="">--Seleccione--</option>
               @foreach($tipoHabitaciones as $lista_tipoHabitaciones)
                 <option value="{{$lista_tipoHabitaciones->id}}"> {{$lista_tipoHabitaciones->descripcion}}</option>
               @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="agencia_id" class="my-0" ><strong>Hotel:</strong></label>
            <select name="agencia_id" id="agencia_id"  class="form-control selectpicker border" data-live-search="true" >
               <option value="">--Seleccione--</option>
               @foreach($agencias as $lista_agencias)
                 <option value="{{$lista_agencias->agencia_id}}"> {{$lista_agencias->agencia}}</option>
               @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="estado_habitacion_id" class="my-0" ><strong>Estado Habitacion:</strong></label>
            <select name="estado_habitacion_id" id="estado_habitacion_id"  class="form-control selectpicker border" data-live-search="true" >
               <option value="">--Seleccione--</option>
               @foreach($estadoHabitaciones as $lista_estado_habitacion)
                 <option value="{{$lista_estado_habitacion->id}}"> {{$lista_estado_habitacion->descripcion}}</option>
               @endforeach
            </select>
        </div>
    </div>

</div>
