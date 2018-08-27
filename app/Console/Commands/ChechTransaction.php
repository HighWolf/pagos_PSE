<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\models\Transacciones;
use App\Http\Controllers\API\TransaccionesController;
use Illuminate\Support\Facades\Log;

class ChechTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revision periodica del estado de las transacciones';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $transaccion= Transacciones::where( 'transactionState', 'PENDING' )->get( [ 'reference'] );
        foreach ($transaccion as $value) {
            $controller= new TransaccionesController();
            $controller->getTransactionResult($value->reference, 1);
        }
    }
}
