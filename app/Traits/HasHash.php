<?php

namespace App\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait HasHash
{
    /**
     * Get hashed attribute.
     *
     * @return mixed
     */
    public function getHashAttribute()
    {
        return Hashids::encode($this->getKey());
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->hash;
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->hash($value)->firstOrFail();
    }

    /**
     * Scope for where clause on hashed id.
     *
     * @param $query
     * @param $hash
     * @return mixed
     */
    public function scopeHash($query, $hash)
    {
        $values = explode('-', $hash);
        $hash = end($values);

        return $query->whereId(
            $this->decodeHash($hash)
        );
    }

    public function scopeHashes($query, $hashes)
    {
        $hashes = collect($hashes)
            ->map(function ($hash) {
                $parts = explode('-', $hash);
                return $this->decodeHash(end($parts));
            })->toArray();
        return $query->whereIn('id', $hashes);
    }

    protected function decodeHash($hash)
    {
        $data = Hashids::decode($hash);
        return intval(end($data));
    }
}
