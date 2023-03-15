<?php
namespace App\Entidades\Business;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table='pro_producto';
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=['descripcion',
                         'categoria_id',
                         'usuario_alta_id',
                         'usuario_modif_id',
                         'precio',
                         'estado',
                         'fecha_creacion',
                         'fecha_modificacion'
                        ];
}
