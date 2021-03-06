<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Transacciones extends Model
{
    protected $fillable = ['bankCode', 'bankInterface', 'returnURL', 'reference', 'description', 'language', 'currency', 'totalAmount', 'taxAmount', 'devolutionBase', 'tipAmount', 'payer', 'buyer', 'shipping', 'ipAddress', 'userAgent', "transactionID", "sessionID", "returnCode", "trazabilityCode", "transactionCycle", "bankCurrency", "bankFactor", "bankURL", "responseCode", "responseReasonCode", "responseReasonText", "bankProcessDate", "transactionState", "requestDate", "onTest"];
}
