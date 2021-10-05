<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommended extends Model
{
    use HasFactory;
    protected $table="recommendeds";
    protected $fillable=['post_id','arrangement','status'];
    public function getPost(){
        return $this->hasOne(Post::class,'id','post_id')->withDefault();
    }
}
