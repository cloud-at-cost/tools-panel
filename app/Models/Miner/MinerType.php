<?php

namespace App\Models\Miner;

use App\Traits\HasHash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MinerType extends Model
{
    use HasFactory, SoftDeletes, HasHash;

    protected $guarded = [];
}
