<?php

namespace App\Models\Bitcoin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BitcoinMarketValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
}
