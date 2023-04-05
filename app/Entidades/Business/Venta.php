<?php
namespace App\Entidades\Business;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table='con_venta';
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=['fecha',
                         'nombre',
                         'detalle',
                         'usuario_alta_id',
                         'usuario_modif_id',
                         'estado',
                         'fecha_creacion',
                         'fecha_modificacion'
                        ];
}
