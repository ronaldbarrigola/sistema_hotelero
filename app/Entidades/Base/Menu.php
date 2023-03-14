<?php

namespace App\Entidades\Base;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table="bas_menu";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        "id",
        "nombre",
        "orden",
        "icono",
        "url",
        "fecha_alta",
        "usuario_alta_id",
        "estado"
    ];

    //relacion con roles
    public function roles(){
        return $this
                ->belongsToMany('App\Entidades\Base\Rol','bas_rol_menu','menu_id','rol_id')
                ->withPivot('fecha_alta', 'usuario_alta_id','estado');
    }

    //relacion submenus
    public function subMenus(){
        return $this->hasMany('App\Entidades\Base\Menu','padre_id','id');
    }
    
    //relacion menuPadre
    public function menuPadre(){
        return $this->belongsTo('App\Entidades\Base\Menu','padre_id','id');
    }
}
