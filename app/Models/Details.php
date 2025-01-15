<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Details extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'pan',
        'email',
        'phone_no',
        'dmait_acc',
        'saving_acc',
        'bank_name',
    ];
}
