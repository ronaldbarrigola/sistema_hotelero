<?php
namespace App\Entidades\Business;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table='pro_producto';
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=['descripcion',
                         'categoria_id',
                         'usuario_alta_id',
                         'usuario_modif_id',
                         'estado',
                         'fecha_creacion',
                         'fecha_modificacion'
                        ];

    //Relacion uno a muchos
    //Relacion entre la tabla pro_producto(1) y la tabla pro_hotel_producto(N)
    //return $this->hasMany(Comment::class, 'foreign_key');
    //return $this->hasMany(Comment::class, 'foreign_key', 'local_key');
    public function hotelProductos()
    {
       return $this->hasMany(HotelProducto::class,'producto_id','id');
    }

}
