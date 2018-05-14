<?php

use Illuminate\Database\Seeder;
use App\models\Departamentos;

class DepartamentosTableSeeder extends Seeder
{

    /**
     * Lista de departamentos.
     */
    private $data= array(
        array('codigo_dane'=> '05', 'nombre'=> 'ANTIOQUIA'),
        array('codigo_dane'=> '08', 'nombre'=> 'ATLANTICO'),
        array('codigo_dane'=> '11', 'nombre'=> 'BOGOTA'),
        array('codigo_dane'=> '13', 'nombre'=> 'BOLIVAR'),
        array('codigo_dane'=> '15', 'nombre'=> 'BOYACA'),
        array('codigo_dane'=> '17', 'nombre'=> 'CALDAS'),
        array('codigo_dane'=> '18', 'nombre'=> 'CAQUETA'),
        array('codigo_dane'=> '19', 'nombre'=> 'CAUCA'),
        array('codigo_dane'=> '20', 'nombre'=> 'CESAR'),
        array('codigo_dane'=> '23', 'nombre'=> 'CORDOBA'),
        array('codigo_dane'=> '25', 'nombre'=> 'CUNDINAMARCA'),
        array('codigo_dane'=> '27', 'nombre'=> 'CHOCO'),
        array('codigo_dane'=> '41', 'nombre'=> 'HUILA'),
        array('codigo_dane'=> '44', 'nombre'=> 'LA GUAJIRA'),
        array('codigo_dane'=> '47', 'nombre'=> 'MAGDALENA'),
        array('codigo_dane'=> '50', 'nombre'=> 'META'),
        array('codigo_dane'=> '52', 'nombre'=> 'NARIÑO'),
        array('codigo_dane'=> '54', 'nombre'=> 'N. DE SANTANDER'),
        array('codigo_dane'=> '63', 'nombre'=> 'QUINDIO'),
        array('codigo_dane'=> '66', 'nombre'=> 'RISARALDA'),
        array('codigo_dane'=> '68', 'nombre'=> 'SANTANDER'),
        array('codigo_dane'=> '70', 'nombre'=> 'SUCRE'),
        array('codigo_dane'=> '73', 'nombre'=> 'TOLIMA'),
        array('codigo_dane'=> '76', 'nombre'=> 'VALLE DEL CAUCA'),
        array('codigo_dane'=> '81', 'nombre'=> 'ARAUCA'),
        array('codigo_dane'=> '85', 'nombre'=> 'CASANARE'),
        array('codigo_dane'=> '86', 'nombre'=> 'PUTUMAYO'),
        array('codigo_dane'=> '88', 'nombre'=> 'SAN ANDRES'),
        array('codigo_dane'=> '91', 'nombre'=> 'AMAZONAS'),
        array('codigo_dane'=> '94', 'nombre'=> 'GUAINIA'),
        array('codigo_dane'=> '95', 'nombre'=> 'GUAVIARE'),
        array('codigo_dane'=> '97', 'nombre'=> 'VAUPES'),
        array('codigo_dane'=> '99', 'nombre'=> 'VICHADA')
    );

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data as $value) {
            $tipo= new Departamentos($value);
            $tipo->save();
        }
    }
}
