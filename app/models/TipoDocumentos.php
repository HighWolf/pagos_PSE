<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumentos extends Model
{
    public $timestamps = false;

    public function enidades()
    {
		return $this->belongsTo('App\models\Entidades', 'tipo_doc');
	}
}
