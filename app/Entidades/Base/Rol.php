<?php

namespace App\Entidades\Base;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table="bas_rol";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        "codigo",
        "nombre",
        "descripcion",
        "estado"
    ];
    
    //relacion con usuarios
    public function usuarios()
    {
        return $this
            ->belongsToMany('App\Entidades\Base\User','bas_usuario_rol','rol_id','usuario_id');
            //->withTimestamps();
    }

    //relacion con menus
    public function menus(){
        $menus=$this
                ->belongsToMany('App\Entidades\Base\Menu','bas_rol_menu','rol_id','menu_id')
                ->withPivot('fecha_alta', 'usuario_alta_id','estado');
        return $menus->OrderBy('orden');//retorna los menus correspondientes al Rol actual ordenados por el campo orden de la tabla menu
    }

    //relacion con menus
    public function objetos(){
        $objeto=$this
                ->belongsToMany('App\Entidades\Base\Objeto','bas_rol_objeto','rol_id','objeto_id')
                ->withPivot('fecha_alta', 'usuario_alta_id','estado');
        return $objeto->OrderBy('id');//retorna los objetos correspondientes al Rol actual ordenados por el campo orden de la tabla menu
    }

}
