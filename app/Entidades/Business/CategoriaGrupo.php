<?php
namespace App\Entidades\Business;
use Illuminate\Database\Eloquent\Model;

class CategoriaGrupo extends Model
{
    protected $table='pro_grupo';
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=['grupo',
                         'estado',
                         'fecha_creacion',
                         'fecha_modificacion'
                        ];
}
