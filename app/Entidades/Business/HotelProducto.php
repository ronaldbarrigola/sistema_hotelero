<?php
namespace App\Entidades\Business;
use Illuminate\Database\Eloquent\Model;

class HotelProducto extends Model
{
    protected $table='pro_hotel_producto';
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=['producto_id',
                         'agencia_id',
                         'usuario_alta_id',
                         'usuario_modif_id',
                         'precio',
                         'activado',
                         'estado',
                         'fecha_creacion',
                         'fecha_modificacion'
                        ];

    //Relacion uno a muchos(Inversa)
    //Relacion entre la tabla pro_producto(1) y la tabla pro_hotel_producto(N)
    //return $this->belongsTo(Post::class, 'foreign_key');
    //return $this->belongsTo(Post::class, 'foreign_key', 'owner_key');
    public function producto()
    {
        return $this->belongsTo(Producto::class,'producto_id','id');
    }
}
