
<div class="row">

    <input type="hidden" name="cliente_id" id="cliente_id">

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="pais_id" class="my-0" ><strong>Pais:</strong></label>
            <select name="pais_id" id="pais_id" required class="form-control selectpicker border" data-live-search="true" >
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
           <select name="ciudad_id" id="ciudad_id" required class="form-control selectpicker border" data-live-search="true" >

           </select>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
           <label for="profesion_id" class="my-0" ><strong>Profesion:</strong></label>
           <select name="profesion_id" id="profesion_id"  class="form-control selectpicker border" data-live-search="true" >
              <option value="">--Seleccione--</option>
              @foreach($profesiones as $lista_profesion)
              <option value="{{$lista_profesion->id}}"> {{$lista_profesion->descripcion}}</option>
            @endforeach
           </select>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
           <label for="empresa_id" class="my-0" ><strong>Empresa:</strong></label>
           <select name="empresa_id" id="empresa_id"  class="form-control selectpicker border" data-live-search="true" >
              <option value="">--Seleccione--</option>
              @foreach($empresas as $lista_empresa)
              <option value="{{$lista_empresa->id}}"> {{$lista_empresa->descripcion}}</option>
            @endforeach
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
