<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distination extends Model
{
    use HasFactory;

    protected $table = 'distinations';
    protected $guarded = [];


    public function link(){
        return $this->belongsTo(Link::class);
    }
    public function stats(){
        return $this->hasMany(Stats::class);
    }
}
