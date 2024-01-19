<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer',
        'USD',
        'Rate',
        'NRP',
        'Ad_Account',
        'Payment',
        'Duration',
        'Quantity',
        'Status',
        'Ad_Nature_Page',
        'admin',
        'is_complete',
        'advance',
    ];
}
