<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'send_user_id', 'receive_user_id', 'sending_amount', 'current_rate', 'convert_amount', 'transaction_date'
    ];

    public function users()
    {   
        return $this->belongsToMany(Transaction::class);
    }
}