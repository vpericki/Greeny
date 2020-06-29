<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RewardCode extends Model
{
    protected $fillable = [
        'unique_code', 'reward'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function users() {
        return $this->belongsToMany(User::class);
    }
}
