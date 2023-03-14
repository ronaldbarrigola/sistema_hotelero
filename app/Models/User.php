<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Entidades\Base\Rol;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table="bas_usuario";
    //protected $primaryKey="id";

    protected $fillable = [
        'id','login', 'password','email', 'api_token',
        'sucursal_id','agencia_id',
        'usuario_alta_id','estado'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

     //Relacion 1 a 1 Con Persona
     public function persona()
     {
         return $this->hasOne('App\Entidades\Base\Persona','id');//'id' es la columna de relacion en bas_persona
     }

     public function sucursal(){
        return $this->belongsTo('App\Entidades\Base\Sucursal','sucursal_id','id');
     }

     public function agencia(){
        return $this->belongsTo('App\Entidades\Base\Agencia','agencia_id','id');
     }

    public function roles()
    {
        return $this
            ->belongsToMany('App\Entidades\Base\Rol','bas_usuario_rol','usuario_id','rol_id')
            ->withPivot('fecha_alta', 'usuario_alta_id','estado');
            //->withTimestamps();
    }

    //funcion para obtener el rol que el usuario selecciono.
    public function obtenerRolSession()
    {
        $rolActual=Rol::find(session()->get("ROL_ID"));
        return $rolActual;
    }

    //funcion para saber si el usuario tiene  un  rol registrado en session
    public function tieneRolSession()
    {
        if(session()->get("ROL_ID")!=null)
            return true;
        return false;
    }


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];
}
