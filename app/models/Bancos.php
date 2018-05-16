<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Bancos extends Model
{
	protected $fillable = ['bankCode', 'bankName'];
	
    public $timestamps = false;
}
