
<div class="row">

    <input type="hidden" name="categoria_id" id="categoria_id">

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="descripcion" class="my-0"><strong>Categoria:</strong></label>
            <input type="text" name="descripcion" id="descripcion" required maxlength="100" class="form-control">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="grupo_id" class="my-0" ><strong>Grupo:</strong></label>
            <select name="grupo_id" id="grupo_id" required class="form-control selectpicker border" data-live-search="true" >
               <option value="">--Seleccione--</option>
               @foreach($grupos as $lista_grupos)
                 <option value="{{$lista_grupos->id}}"> {{$lista_grupos->grupo}}</option>
               @endforeach
            </select>
        </div>
    </div>

</div>
