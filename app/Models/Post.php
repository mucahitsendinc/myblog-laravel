<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table="posts";

    public function getImage(){
        return $this->hasOne(Image::class,'id','image_id');
    }
    public function getTags(){
        return $this->hasMany(Tag::class,'post_id')->select(['tag','id']);
    }
    public function getUrl(){
        return $this->hasOne(Page::class,'id','page_id')->select(['url']);
    }
    public function getComments(){
        return $this->hasMany(Comment::class,'post_id','id')->where('status','=',0)->select(['id','name','comment','created_at']);
    }
    public function getRecommendeds(){
        return $this->hasMany(Recommended::class,'post_id','id')->where('status','=',0);
    }
}
