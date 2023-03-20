
<div class="row">

    <input type="hidden" name="producto_id" id="producto_id">

    <div class="col-12">
        <div class="form-group">
            <label for="descripcion" class="my-0"><strong>Producto:</strong></label>
            <input type="text" name="descripcion" id="descripcion" maxlength="100" class="form-control" required>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
           <label for="categoria_id" class="my-0" ><strong>Categoria:</strong></label>
           <select name="categoria_id" id="categoria_id"  class="form-control selectpicker border" required data-live-search="true" >
              <option value="">--Seleccione--</option>
              @foreach($categorias as $lista_categoria)
              <option value="{{$lista_categoria->id}}"> {{$lista_categoria->descripcion}}</option>
            @endforeach
           </select>
        </div>
    </div>
</div>
