<?php
namespace App\Entidades\Business;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table='pro_categoria';
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=['descripcion',
                         'estado',
                         'fecha_creacion',
                         'fecha_modificacion'
                        ];

    public function producto()
    {
        return $this->belongsTo('App\Business\Producto');  //Relacion N:1
    }
}
