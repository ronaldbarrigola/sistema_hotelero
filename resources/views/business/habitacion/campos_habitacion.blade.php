
<div class="row">

    <input type="hidden" name="habitacion_id" id="habitacion_id">

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="num_habitacion" class="my-0"><strong>Numero de Habitacion:</strong></label>
            <input type="text" name="num_habitacion" id="num_habitacion" required class="form-control">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="descripcion" class="my-0"><strong>Descripcion Habitacion:</strong></label>
            <input type="text" name="descripcion" id="descripcion" required class="form-control">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
           <label for="piso" class="my-0"><strong>Piso:</strong></label>
           <input type="text" name="piso" id="piso" required class="form-control">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="precio" class="my-0"><strong>Precio:</strong></label>
            <div class="input-group-prepend">
                <span class="input-group-text"><strong>Bs.</strong></span>
                <input type="number" name="precio" id="precio" required class="form-control">
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="tipo_habitacion_id" class="my-0" ><strong>Tipo Habitacion:</strong></label>
            <select name="tipo_habitacion_id" id="tipo_habitacion_id" required class="form-control selectpicker border" data-live-search="true" >
               <option value="">--Seleccione--</option>
               @foreach($tipoHabitaciones as $lista_tipoHabitaciones)
                 <option value="{{$lista_tipoHabitaciones->id}}"> {{$lista_tipoHabitaciones->tipo_habitacion}}</option>
               @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="estado_habitacion_id" class="my-0" ><strong>Estado Habitacion:</strong></label>
            <select name="estado_habitacion_id" id="estado_habitacion_id" required class="form-control selectpicker border" data-live-search="true" >
               <option value="">--Seleccione--</option>
               @foreach($estadoHabitaciones as $lista_estado_habitacion)
                 <option value="{{$lista_estado_habitacion->id}}"> {{$lista_estado_habitacion->descripcion}}</option>
               @endforeach
            </select>
        </div>
    </div>

</div>
