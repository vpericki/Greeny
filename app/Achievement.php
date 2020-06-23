<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'name', 'description', 'required_points'
    ];

    public function users() {
        return $this -> belongsToMany(User::class);
    }
}
