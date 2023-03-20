<?php
namespace App\Entidades\Business;
use Illuminate\Database\Eloquent\Model;

class PrecioLIsta extends Model
{
    protected $table='pro_hotel_producto';
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=['producto_id',
                         'agencia_id',
                         'usuario_alta_id',
                         'usuario_modif_id',
                         'precio',
                         'activado',
                         'estado',
                         'fecha_creacion',
                         'fecha_modificacion'
                        ];
}
