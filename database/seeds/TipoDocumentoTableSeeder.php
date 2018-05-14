<?php

use Illuminate\Database\Seeder;
use App\models\TipoDocumentos;

class TipoDocumentoTableSeeder extends Seeder
{

    /**
     * Lista de tipos de documento.
     */
    private $data= array(
        array('tipo'=> 'CC', 'desc'=> 'Cédula de ciudanía'),
        array('tipo'=> 'CE', 'desc'=> 'Cédula de extranjería'),
        array('tipo'=> 'TI', 'desc'=> 'Tarjeta de identidad'),
        array('tipo'=> 'PPN', 'desc'=> 'Pasaporte'),
        array('tipo'=> 'NIT', 'desc'=> 'Número de identificación tributaria'),
        array('tipo'=> 'SSN', 'desc'=> 'Social Security Number')
    );

    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        foreach ($this->data as $value) {
            $tipo= new TipoDocumentos($value);
            $tipo->save();
        }
    }
}
