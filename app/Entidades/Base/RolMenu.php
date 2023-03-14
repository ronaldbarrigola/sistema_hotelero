<?php

namespace App\Entidades\Base;

use Illuminate\Database\Eloquent\Model;

class RolMenu extends Model
{
    protected $table="bas_rol_menu";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        "rol_id",
        "menu_id",
        "fecha_alta",
        "usuario_alta_id",
        "estado"
    ];

}
