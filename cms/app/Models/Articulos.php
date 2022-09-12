<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulos extends Model
{
    use HasFactory;
    protected $table = 'articulos';

    /*==================================
     = INNER JOIN DESDE EL MODELO = 
     ===================================*/
    
     public function categorias(){

        return $this->belongsTo('App\Models\Categorias', 'id_cat', 'id_categoria');

     }


}
