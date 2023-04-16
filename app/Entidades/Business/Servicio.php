<?php
namespace App\Entidades\Business;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table='res_servicio';
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=['descripcion',
                         'estado',
                         'fecha_creacion',
                         'fecha_modificacion'
                        ];

    //Relacion 1 a uno
    public function reservas()
    {
        return $this->hasMany(Reserva::class,'servicio_id','id');
    }
}
