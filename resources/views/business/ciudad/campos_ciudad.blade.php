
<div class="row">

    <input type="hidden" name="ciudad_id" id="ciudad_id">

    <div class="col-12">
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

    <div class="col-12">
        <div class="form-group">
            <label for="descripcion" class="my-0"><strong>Ciudad:</strong></label>
            <input type="text" name="descripcion" id="descripcion" required maxlength="200" class="form-control">
        </div>
    </div>

</div>
