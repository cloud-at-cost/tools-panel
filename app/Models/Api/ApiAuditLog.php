<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiAuditLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function setParametersAttribute(?array $value)
    {
        if(empty($value)) {
            return;
        }

        $this->attributes['parameters'] = json_encode($value);
    }

    public function getParametersAttribute(): ?array
    {
        $value = $this->attributes['parameters'];

        if(!$value) {
            return null;
        }

        return json_decode($value);
    }
}
