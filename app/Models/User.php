<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'cpf', 'email', 'password', 'type'
    ];

    protected $hidden = [
        'password',
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
}
