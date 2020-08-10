<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BalanceEntry extends Model
{
    protected $fillable = ['label', 'date', 'amount'];
}
