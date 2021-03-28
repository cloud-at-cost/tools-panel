<?php

namespace App\Models\CloudAtCost\Server;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Platform extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @return HasMany
     */
    public function operatingSystems(): HasMany
    {
        return $this->hasMany(PlatformOperatingSystem::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->slug = $value;
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
