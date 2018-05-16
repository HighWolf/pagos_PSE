<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Entidades extends Model
{
    protected $fillable = ['tipo_doc', 'documento', 'nombres', 'apellidos', 'compania', 'email', 'direccion', 'ciudad', 'telefono', 'celular'];

	public function tipo_doc(){
		return $this->hasOne('App\models\TipoDocumentos', 'id');
	}
}
