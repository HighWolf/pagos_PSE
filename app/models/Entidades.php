<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Entidades extends Model
{
    protected $fillable = ['documentType', 'document', 'firstName', 'lastName', 'company', 'emailAddress', 'address', 'city', 'phone', 'mobile'];

	public function documentType(){
		return $this->hasOne('App\models\TipoDocumentos', 'id');
	}
}
