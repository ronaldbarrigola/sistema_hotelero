<?php
namespace App\Entidades\Business;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table='res_canal_reserva';
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=['nombre',
                         'estado',
                         'fecha_creacion',
                         'fecha_modificacion'
                        ];

    //Relacion 1 a uno
    public function reservas()
    {
        return $this->hasMany(Reserva::class,'canal_reserva_id','id');
    }
}
