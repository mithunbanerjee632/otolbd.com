<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function categories(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function brands(){
        return $this->belongsTo(Brands::class,'brand_id');
    }
}
