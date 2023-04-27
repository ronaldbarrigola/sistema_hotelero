<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    protected $table='con_forma_pago';
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=['descripcion',
                         'estado',
                         'fecha_creacion',
                         'fecha_modificacion'
                        ];
}
