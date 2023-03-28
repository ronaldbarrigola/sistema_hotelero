<?php
namespace App\Entidades\Business;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table='res_servicio';
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=['estado',
                         'fecha_creacion',
                         'fecha_modificacion'
                        ];
}
