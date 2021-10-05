<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    protected $table="pages";

    public function getPageToPost(){
        return $this->belongsTo(Post::class,'id','page_id')->withDefault()->select('title','description','page_id','image_id');
    }

}
