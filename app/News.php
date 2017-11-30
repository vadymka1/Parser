<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = ['news_id', 'link', 'title' , 'date', 'tags', 'views'];


}