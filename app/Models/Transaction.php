<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'payer_wallet_id', 'payee_wallet_id', 'value'
    ];
}
