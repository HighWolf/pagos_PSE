<?php

use Illuminate\Database\Seeder;
use App\models\InterfazBancos;

class InterfazBancosTableSeeder extends Seeder
{
    /**
     * Lista de las interfaces de bancos.
     */
    private $data= array(
        array('nombre'=> 'PERSONAS'),
        array('nombre'=> 'EMPRESAS')
    );

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data as $value) {
            $tipo= new InterfazBancos($value);
            $tipo->save();
        }
    }
}
